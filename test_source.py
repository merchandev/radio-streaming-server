import socket
import time
import sys

# Configuration
HOST = 'localhost'
PORT = 8000
MOUNT = '/stream'
PASSWORD = 'mistream'
FILENAME = 'test_audio.mp3' # You would need a dummy mp3 file

def connect():
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    try:
        s.connect((HOST, PORT))
    except Exception as e:
        print(f"Could not connect to {HOST}:{PORT} - {e}")
        return

    print("Connected to Icecast server")

    # Icecast Source Protocol (HTTP-like)
    headers = [
        f"SOURCE {MOUNT} ICE/1.0",
        f"Authorization: Basic {PASSWORD}", # Note: This usually needs base64 encoding of user:pass for HTTP
        "content-type: audio/mpeg",
        "ice-public: 0",
        "ice-name: Test Stream",
        "ice-description: Testing Icecast",
        "\r\n"
    ]
    
    # Actually for Icecast source client, it sends:
    # SOURCE /mount ICE/1.0
    # content-type: ...
    # Authorization: Basic ... (user:pass base64) -> user is 'source'
    
    # But usually simple clients just send the password in a specific way or use the header.
    # Standard libshout/icecast way:
    # Authorization is 'source:password' base64 encoded.
    
    import base64
    auth_str = f"source:{PASSWORD}"
    auth_b64 = base64.b64encode(auth_str.encode()).decode()
    
    headers = [
        f"SOURCE {MOUNT} ICE/1.0",
        f"Authorization: Basic {auth_b64}",
        "Content-Type: audio/mpeg",
        "Ice-Public: 0",
        "Ice-Name: Test Stream",
        "Ice-Description: Testing python source",
        "\r\n" # Empty line to end headers
    ]

    s.send(''.join([h + "\r\n" for h in headers]).encode())

    # Check response (should be 200 OK)
    # response = s.recv(1024)
    # print("Server Response:", response.decode())

    # Start streaming dummy data (silence or pattern) if no file
    print("Streaming dummy data...")
    try:
        while True:
            # Send 1 second of dummy MP3-like frames or just noise? 
            # Noise might be annoying. Just 0 bytes might not trigger player.
            # Best is to just verify connection established.
            # For this test, we just want to see if we get disconnected immediately.
            
            # Send a chunk
            s.send(b'\x00' * 4096)
            time.sleep(0.1)
    except KeyboardInterrupt:
        print("Stopping...")
    except BrokenPipeError:
        print("Disconnected by server.")
    finally:
        s.close()

if __name__ == "__main__":
    connect()
