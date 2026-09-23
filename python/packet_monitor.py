from scapy.all import sniff, IP, TCP
from collections import defaultdict, deque
from datetime import datetime, timedelta
import requests

# -------------------------------------------------
# SYSTEM CONFIGURATION
# -------------------------------------------------

TARGET_IP = "10.55.241.109"

LARAVEL_API_URL = "http://127.0.0.1:8002/api/network-events"

LARAVEL_API_KEY = "a043c17db2f4ee83a4c70459baa86743a5094bc21dc6c72a445d4f7e15491bf7"

# Detection thresholds
PORT_SCAN_THRESHOLD = 5
REPEATED_CONNECTION_THRESHOLD = 5
REPEATED_CONNECTION_WINDOW = 30

# -------------------------------------------------
# DETECTION STORAGE
# -------------------------------------------------

# Unique destination ports contacted by each source.
source_ports = defaultdict(set)

# Connection attempt timestamps for each source.
connection_attempts = defaultdict(deque)

# Prevent duplicate alerts.
reported_port_scans = set()
reported_repeated_connections = set()


# -------------------------------------------------
# SEND SECURITY EVENT TO LARAVEL
# -------------------------------------------------

def send_event_to_laravel(
    source_ip,
    destination_ip,
    source_port,
    destination_port,
    event_type,
    severity,
    description
):
    data = {
        "source_ip": source_ip,
        "destination_ip": destination_ip,
        "source_port": source_port,
        "destination_port": destination_port,
        "protocol": "TCP",
        "event_type": event_type,
        "severity": severity,
        "description": description,
        "status": "unresolved",
    }

    try:
        response = requests.post(
    LARAVEL_API_URL,
    json=data,
    headers={
        "X-IDS-API-Key": LARAVEL_API_KEY
    },
    timeout=5
)

        if response.status_code == 201:
            print("✓ Security event sent to Laravel successfully.")

        else:
            print(
                f"⚠ Laravel API returned "
                f"status {response.status_code}"
            )
            print(response.text)

    except requests.RequestException as error:
        print()
        print("⚠ Could not send event to Laravel.")
        print(f"Reason: {error}")
        print()


# -------------------------------------------------
# PACKET PROCESSING
# -------------------------------------------------

def process_packet(packet):

    # Only process IPv4 TCP packets.
    if not packet.haslayer(IP) or not packet.haslayer(TCP):
        return

    source_ip = packet[IP].src
    destination_ip = packet[IP].dst
    source_port = packet[TCP].sport
    destination_port = packet[TCP].dport

    # Only monitor traffic going to this computer.
    if destination_ip != TARGET_IP:
        return

    # Only process TCP SYN connection attempts.
    tcp_flags = packet[TCP].flags

    if not (tcp_flags & 0x02):
        return

    now = datetime.now()

    # -------------------------------------------------
    # PORT SCAN TRACKING
    # -------------------------------------------------

    source_ports[source_ip].add(destination_port)

    port_count = len(source_ports[source_ip])

    # -------------------------------------------------
    # REPEATED CONNECTION TRACKING
    # -------------------------------------------------

    connection_attempts[source_ip].append(now)

    cutoff_time = now - timedelta(
        seconds=REPEATED_CONNECTION_WINDOW
    )

    while (
        connection_attempts[source_ip]
        and connection_attempts[source_ip][0] < cutoff_time
    ):
        connection_attempts[source_ip].popleft()

    connection_count = len(connection_attempts[source_ip])

    # -------------------------------------------------
    # DISPLAY TRAFFIC
    # -------------------------------------------------

    print(
        f"[{now.strftime('%Y-%m-%d %H:%M:%S')}] "
        f"{source_ip} -> {destination_ip}:{destination_port} "
        f"| Unique ports: {port_count} "
        f"| Attempts in {REPEATED_CONNECTION_WINDOW}s: "
        f"{connection_count}"
    )

    # -------------------------------------------------
    # PORT SCAN DETECTION
    # -------------------------------------------------

    if (
        port_count >= PORT_SCAN_THRESHOLD
        and source_ip not in reported_port_scans
    ):
        reported_port_scans.add(source_ip)

        print()
        print("=" * 55)
        print("⚠️ POSSIBLE PORT SCAN DETECTED")
        print(f"Source IP: {source_ip}")
        print(f"Target IP: {TARGET_IP}")
        print(f"Unique ports contacted: {port_count}")
        print("=" * 55)
        print()

        send_event_to_laravel(
            source_ip=source_ip,
            destination_ip=destination_ip,
            source_port=source_port,
            destination_port=destination_port,
            event_type="Port Scan",
            severity="high",
            description=(
                f"Possible port scan detected. "
                f"Source contacted {port_count} "
                f"unique destination ports."
            )
        )

    # -------------------------------------------------
    # REPEATED CONNECTION DETECTION
    # -------------------------------------------------

    if (
        connection_count >= REPEATED_CONNECTION_THRESHOLD
        and source_ip not in reported_repeated_connections
    ):
        reported_repeated_connections.add(source_ip)

        print()
        print("=" * 55)
        print("⚠️ REPEATED CONNECTION ATTEMPTS DETECTED")
        print(f"Source IP: {source_ip}")
        print(f"Target IP: {TARGET_IP}")
        print(
            f"Connection attempts within "
            f"{REPEATED_CONNECTION_WINDOW} seconds: "
            f"{connection_count}"
        )
        print("=" * 55)
        print()

        send_event_to_laravel(
            source_ip=source_ip,
            destination_ip=destination_ip,
            source_port=source_port,
            destination_port=destination_port,
            event_type="Repeated Connection",
            severity="medium",
            description=(
                f"Repeated connection attempts detected. "
                f"Source made {connection_count} "
                f"connection attempts within "
                f"{REPEATED_CONNECTION_WINDOW} seconds."
            )
        )


# -------------------------------------------------
# START IDS MONITOR
# -------------------------------------------------

print("==========================================")
print(" Network Intrusion Detection System")
print(" Packet Monitor")
print("==========================================")
print()

print(f"Monitoring target: {TARGET_IP}")
print(f"Laravel API: {LARAVEL_API_URL}")
print(
    f"Port scan threshold: "
    f"{PORT_SCAN_THRESHOLD} unique ports"
)
print(
    f"Repeated connection threshold: "
    f"{REPEATED_CONNECTION_THRESHOLD} attempts "
    f"in {REPEATED_CONNECTION_WINDOW} seconds"
)
print()
print("Monitoring TCP SYN connection attempts...")
print("Press Ctrl+C to stop.")
print()

try:
    sniff(
        filter=f"tcp and dst host {TARGET_IP}",
        prn=process_packet,
        store=False
    )

except KeyboardInterrupt:
    print()
    print("Packet monitoring stopped.")