from scapy.all import sniff, IP, TCP, ICMP
from collections import defaultdict, deque
from datetime import datetime, timedelta
import os
import requests


# =================================================
# SYSTEM CONFIGURATION
# =================================================

TARGET_IP = "10.55.241.109"

LARAVEL_API_URL = "http://127.0.0.1:8002/api/network-events"

# Read the API key from the environment.
LARAVEL_API_KEY = os.getenv("IDS_API_KEY")


# =================================================
# DETECTION THRESHOLDS
# =================================================

# Rule 1: Port scan
PORT_SCAN_THRESHOLD = 5

# Rule 2: Repeated TCP connections
REPEATED_CONNECTION_THRESHOLD = 5
REPEATED_CONNECTION_WINDOW = 30

# Rule 4: Repeated ICMP echo requests
ICMP_THRESHOLD = 5
ICMP_WINDOW = 30


# =================================================
# SUSPICIOUS PORTS
# =================================================

SUSPICIOUS_PORTS = {
    21,    # FTP
    22,    # SSH
    23,    # Telnet
    25,    # SMTP
    53,    # DNS
    80,    # HTTP
    110,   # POP3
    139,   # NetBIOS
    143,   # IMAP
    443,   # HTTPS
    445,   # SMB
    3389,  # RDP
}


# =================================================
# DETECTION STORAGE
# =================================================

# Rule 1
source_ports = defaultdict(set)

# Rule 2
connection_attempts = defaultdict(deque)

# Rule 4
icmp_attempts = defaultdict(deque)

# Prevent duplicate alerts during one monitor session.
reported_port_scans = set()
reported_repeated_connections = set()
reported_suspicious_ports = set()
reported_icmp_activity = set()


# =================================================
# SEND SECURITY EVENT TO LARAVEL
# =================================================

def send_event_to_laravel(
    source_ip,
    destination_ip,
    source_port,
    destination_port,
    protocol,
    event_type,
    severity,
    description,
):
    """
    Send a detected security event to the Laravel IDS API.
    """

    headers = {
        "Accept": "application/json",
        "Content-Type": "application/json",
        "X-IDS-API-Key": LARAVEL_API_KEY,
    }

    payload = {
        "source_ip": source_ip,
        "destination_ip": destination_ip,
        "source_port": source_port,
        "destination_port": destination_port,
        "protocol": protocol,
        "event_type": event_type,
        "severity": severity,
        "description": description,
        "status": "unresolved",
        "detected_at": datetime.now().isoformat(),
    }

    try:

        response = requests.post(
            LARAVEL_API_URL,
            json=payload,
            headers=headers,
            timeout=30,
        )

        if response.status_code == 201:

            print()
            print("✓ Security event sent to Laravel successfully.")

        else:

            print()
            print("✗ Laravel rejected security event.")
            print(f"HTTP status: {response.status_code}")
            print(f"Response: {response.text}")

    except requests.RequestException as error:

        print()
        print("✗ Failed to send security event to Laravel.")
        print(f"Error: {error}")


# =================================================
# PACKET PROCESSING
# =================================================

def process_packet(packet):

    # -------------------------------------------------
    # BASIC IP VALIDATION
    # -------------------------------------------------

    if not packet.haslayer(IP):
        return

    ip = packet[IP]

    source_ip = ip.src
    destination_ip = ip.dst

    # Only monitor traffic directed at our target.
    if destination_ip != TARGET_IP:
        return

    now = datetime.now()


    # =================================================
    # RULE 4: ICMP / PING ACTIVITY
    # =================================================

    if packet.haslayer(ICMP):

        icmp = packet[ICMP]

        # ICMP type 8 = Echo Request
        if icmp.type == 8:

            icmp_attempts[source_ip].append(now)

            cutoff_time = now - timedelta(
                seconds=ICMP_WINDOW
            )

            while (
                icmp_attempts[source_ip]
                and icmp_attempts[source_ip][0] < cutoff_time
            ):
                icmp_attempts[source_ip].popleft()

            icmp_count = len(
                icmp_attempts[source_ip]
            )

            print(
                f"[{now.strftime('%Y-%m-%d %H:%M:%S')}] "
                f"{source_ip} -> {destination_ip} "
                f"| ICMP Echo Request "
                f"| Attempts in {ICMP_WINDOW}s: "
                f"{icmp_count}"
            )

            if icmp_count >= ICMP_THRESHOLD:

                if source_ip not in reported_icmp_activity:

                    print()
                    print("=" * 55)
                    print("⚠️ REPEATED ICMP/PING ACTIVITY DETECTED")
                    print(
                        f"Source IP: {source_ip}"
                    )
                    print(
                        f"Target IP: {TARGET_IP}"
                    )
                    print(
                        f"ICMP requests within "
                        f"{ICMP_WINDOW} seconds: "
                        f"{icmp_count}"
                    )
                    print("=" * 55)

                    send_event_to_laravel(
                        source_ip=source_ip,
                        destination_ip=TARGET_IP,
                        source_port=None,
                        destination_port=None,
                        protocol="ICMP",
                        event_type="Repeated ICMP Activity",
                        severity="medium",
                        description=(
                            f"Repeated ICMP Echo Requests detected. "
                            f"{icmp_count} requests were observed "
                            f"within {ICMP_WINDOW} seconds."
                        ),
                    )

                    reported_icmp_activity.add(
                        source_ip
                    )

        return


    # =================================================
    # TCP PROCESSING
    # =================================================

    if not packet.haslayer(TCP):
        return

    tcp = packet[TCP]

    source_port = tcp.sport
    destination_port = tcp.dport


    # -------------------------------------------------
    # ONLY PROCESS TCP SYN CONNECTION ATTEMPTS
    # -------------------------------------------------

    if not (tcp.flags & 0x02):
        return


    # -------------------------------------------------
    # UPDATE CONNECTION TRACKING
    # -------------------------------------------------

    source_ports[source_ip].add(
        destination_port
    )

    connection_attempts[source_ip].append(
        now
    )


    # -------------------------------------------------
    # REMOVE OLD CONNECTION ATTEMPTS
    # -------------------------------------------------

    cutoff_time = now - timedelta(
        seconds=REPEATED_CONNECTION_WINDOW
    )

    while (
        connection_attempts[source_ip]
        and connection_attempts[source_ip][0] < cutoff_time
    ):
        connection_attempts[source_ip].popleft()


    # -------------------------------------------------
    # CURRENT TCP STATISTICS
    # -------------------------------------------------

    unique_ports = len(
        source_ports[source_ip]
    )

    attempts_in_window = len(
        connection_attempts[source_ip]
    )


    # -------------------------------------------------
    # DISPLAY MONITORED TCP TRAFFIC
    # -------------------------------------------------

    print(
        f"[{now.strftime('%Y-%m-%d %H:%M:%S')}] "
        f"{source_ip} -> "
        f"{destination_ip}:{destination_port} | "
        f"Unique ports: {unique_ports} | "
        f"Attempts in 30s: {attempts_in_window}"
    )


    # =================================================
    # RULE 1: PORT SCAN DETECTION
    # =================================================

    if unique_ports >= PORT_SCAN_THRESHOLD:

        if source_ip not in reported_port_scans:

            print()
            print("=" * 55)
            print("⚠️ POSSIBLE PORT SCAN DETECTED")
            print(
                f"Source IP: {source_ip}"
            )
            print(
                f"Target IP: {TARGET_IP}"
            )
            print(
                f"Unique ports contacted: {unique_ports}"
            )
            print("=" * 55)

            send_event_to_laravel(
                source_ip=source_ip,
                destination_ip=TARGET_IP,
                source_port=source_port,
                destination_port=destination_port,
                protocol="TCP",
                event_type="Port Scan",
                severity="high",
                description=(
                    f"Possible port scan detected. "
                    f"Source contacted "
                    f"{unique_ports} unique destination ports."
                ),
            )

            reported_port_scans.add(
                source_ip
            )


    # =================================================
    # RULE 2: REPEATED CONNECTION DETECTION
    # =================================================

    if attempts_in_window >= REPEATED_CONNECTION_THRESHOLD:

        if source_ip not in reported_repeated_connections:

            print()
            print("=" * 55)
            print("⚠️ REPEATED CONNECTION ATTEMPTS DETECTED")
            print(
                f"Source IP: {source_ip}"
            )
            print(
                f"Target IP: {TARGET_IP}"
            )
            print(
                f"Connection attempts within "
                f"{REPEATED_CONNECTION_WINDOW} seconds: "
                f"{attempts_in_window}"
            )
            print("=" * 55)

            send_event_to_laravel(
                source_ip=source_ip,
                destination_ip=TARGET_IP,
                source_port=source_port,
                destination_port=destination_port,
                protocol="TCP",
                event_type="Repeated Connection",
                severity="medium",
                description=(
                    f"Repeated TCP connection attempts detected. "
                    f"{attempts_in_window} attempts were observed "
                    f"within {REPEATED_CONNECTION_WINDOW} seconds."
                ),
            )

            reported_repeated_connections.add(
                source_ip
            )


    # =================================================
    # RULE 3: SUSPICIOUS PORT ACCESS
    # =================================================

    if destination_port in SUSPICIOUS_PORTS:

        suspicious_key = (
            source_ip,
            destination_port
        )

        if suspicious_key not in reported_suspicious_ports:

            print()
            print("=" * 55)
            print("⚠️ SUSPICIOUS PORT ACCESS DETECTED")
            print(
                f"Source IP: {source_ip}"
            )
            print(
                f"Target IP: {TARGET_IP}"
            )
            print(
                f"Destination port: {destination_port}"
            )
            print("=" * 55)

            send_event_to_laravel(
                source_ip=source_ip,
                destination_ip=TARGET_IP,
                source_port=source_port,
                destination_port=destination_port,
                protocol="TCP",
                event_type="Suspicious Port Access",
                severity="medium",
                description=(
                    f"TCP connection attempt detected on "
                    f"commonly targeted port "
                    f"{destination_port}."
                ),
            )

            reported_suspicious_ports.add(
                suspicious_key
            )


# =================================================
# PROGRAM STARTUP
# =================================================

if __name__ == "__main__":

    print("=" * 55)
    print(" Network Intrusion Detection System")
    print(" Packet Monitor")
    print("=" * 55)

    print()
    print(
        f"Monitoring target: {TARGET_IP}"
    )

    print(
        f"Laravel API: {LARAVEL_API_URL}"
    )

    print(
        f"Port scan threshold: "
        f"{PORT_SCAN_THRESHOLD} unique ports"
    )

    print(
        f"Repeated connection threshold: "
        f"{REPEATED_CONNECTION_THRESHOLD} "
        f"attempts in "
        f"{REPEATED_CONNECTION_WINDOW} seconds"
    )

    print(
        f"Suspicious ports monitored: "
        f"{len(SUSPICIOUS_PORTS)}"
    )

    print(
        f"ICMP threshold: "
        f"{ICMP_THRESHOLD} requests in "
        f"{ICMP_WINDOW} seconds"
    )

    print()


    # -------------------------------------------------
    # CHECK API KEY
    # -------------------------------------------------

    if not LARAVEL_API_KEY:

        print(
            "ERROR: IDS_API_KEY environment variable "
            "is not set."
        )

        print(
            "Set the API key before starting the monitor."
        )

        raise SystemExit(1)


    print(
        "Laravel API key: loaded"
    )

    print()

    print(
        "Monitoring TCP SYN and ICMP Echo Requests..."
    )

    print(
        "Press Ctrl+C to stop."
    )

    print()


    # -------------------------------------------------
    # START PACKET CAPTURE
    # -------------------------------------------------

    try:

        sniff(
            filter=(
                f"(tcp and dst host {TARGET_IP}) "
                f"or "
                f"(icmp and dst host {TARGET_IP})"
            ),
            prn=process_packet,
            store=False,
        )

    except KeyboardInterrupt:

        print()
        print(
            "Network IDS monitor stopped."
        )