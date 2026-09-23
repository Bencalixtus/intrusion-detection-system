import socket

target = "10.55.241.109"
ports = [21, 22, 23, 25, 80, 443]

print(f"Testing connection attempts to {target}")

for port in ports:
    sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    sock.settimeout(2)

    result = sock.connect_ex((target, port))

    print(f"Port {port}: result {result}")

    sock.close()

print("Port test completed.")
