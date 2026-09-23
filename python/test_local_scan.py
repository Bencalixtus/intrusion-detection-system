import socket
import time

TARGET_IP = "127.0.0.1"

# Several local ports for the controlled test.
TEST_PORTS = [8000, 8001, 8002, 8080, 8081, 8888]

print("==========================================")
print(" Controlled Local Port Scan Test")
print("==========================================")
print()
print(f"Target: {TARGET_IP}")
print(f"Testing ports: {TEST_PORTS}")
print()

for port in TEST_PORTS:
    print(f"Testing port {port}...")

    sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    sock.settimeout(1)

    try:
        sock.connect_ex((TARGET_IP, port))
    except Exception as error:
        print(f"Error testing port {port}: {error}")
    finally:
        sock.close()

    time.sleep(0.5)

print()
print("Controlled local scan test completed.")
