import subprocess
import time

urls = [
    "https://digicallpartner.com/track-order",
    "https://digicallpartner.com/track-order-quick",
    "https://digicallpartner.com/list-orders2"
]

while True:
    for url in urls:
        try:
            # Use subprocess to call wget with the URL and suppress output
            subprocess.run(['wget', '--quiet', url])
            print(f"Request to {url} successful.")
        except Exception as e:
            # Handle exceptions, e.g., errors in subprocess
            print(f"Error in request to {url}: {e}")

    time.sleep(5)  # Sleep for 5 seconds before the next iteration
