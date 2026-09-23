from scapy.all import IP, TCP, send
import time

TARGET_IP = "10.55.241.109"

# Ports used only for the controlled IDS test.
TEST_PORTS = [65001, 65002, 65003, 65004, 65005, 65006]

print("==========================================")
print(" Controlled IDS Port Scan Test")
print("==========================================")
print()
print(f"Target: {TARGET_IP}")
print(f"Test ports: {TEST_PORTS}")
print()

for port in TEST_PORTS:
    print(f"Sending SYN packet to port {port}...")

    packet = IP(
        dst=TARGET_IP
    ) / TCP(
        dport=port,
        flags="S"
    )

    send(packet, verbose=False)

    time.sleep(0.5)

print()
print("Controlled IDS test completed.")
