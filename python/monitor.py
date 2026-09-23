from collections import defaultdict
from time import time

from scapy.all import sniff, IP, TCP, UDP, conf


# Resolve the active network interface
INTERFACE = conf.ifaces.dev_from_index(10)

# Detection settings
TIME_WINDOW = 10
PORT_THRESHOLD = 5
ALERT_COOLDOWN = 30

# Track destination ports contacted by each source
connection_tracker = defaultdict(list)

# Track the last alert time for each source
last_alert = {}


def detect_port_scan(source_ip, destination_port):
    current_time = time()

    # Remove records outside the detection window
    connection_tracker[source_ip] = [
        record
        for record in connection_tracker[source_ip]
        if current_time - record[0] <= TIME_WINDOW
    ]

    # Add current connection
    connection_tracker[source_ip].append(
        (current_time, destination_port)
    )

    # Get unique destination ports
    unique_ports = {
        record[1]
        for record in connection_tracker[source_ip]
    }

    # Check whether the threshold has been reached
    if len(unique_ports) >= PORT_THRESHOLD:

        # Prevent repeated alerts
        previous_alert = last_alert.get(source_ip, 0)

        if current_time - previous_alert >= ALERT_COOLDOWN:
            last_alert[source_ip] = current_time

            return True, sorted(unique_ports)

    return False, []


def process_packet(packet):
    if IP not in packet:
        return

    source_ip = packet[IP].src
    destination_ip = packet[IP].dst

    destination_port = None
    protocol = "Other"

    if TCP in packet:
        protocol = "TCP"
        destination_port = packet[TCP].dport

    elif UDP in packet:
        protocol = "UDP"
        destination_port = packet[UDP].dport

    # Only analyse TCP and UDP traffic
    if destination_port is None:
        return

    detected, ports = detect_port_scan(
        source_ip,
        destination_port
    )

    if detected:
        print("\n" + "=" * 60)
        print("SECURITY EVENT DETECTED")
        print("=" * 60)
        print("Event Type       : Port Scan")
        print(f"Source IP        : {source_ip}")
        print(f"Destination IP   : {destination_ip}")
        print(f"Protocol         : {protocol}")
        print(f"Ports Detected   : {ports}")
        print("Severity         : High")
        print("Status           : Unresolved")
        print("=" * 60)


print("Network IDS Intrusion Detection Monitor")
print(f"Monitoring interface: {INTERFACE}")
print("Detection rule: Port Scan")
print("Press CTRL+C to stop.")
print("-" * 60)


sniff(
    iface=INTERFACE,
    prn=process_packet,
    store=False
)