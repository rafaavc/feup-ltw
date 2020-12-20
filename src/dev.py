import os
import subprocess
import time
import signal

def refresh():
    subprocess.call(["./build"]) # build joins the files and minifies them (takes a little longer); refresh only joins them
    print("## Refreshed CSS")

previous = {}

def pollForChanges():
    while True:
        refreshed = False
        for filename in os.listdir('css/components'):
            stamp = os.stat('css/components/'+filename).st_mtime
            try:
                if previous[filename] != stamp:
                    if not refreshed:
                        refresh()
                        refreshed = True
                    previous[filename] = stamp
                
            except KeyError:
                if not refreshed:
                    refresh()
                    refreshed = True
                previous[filename] = stamp
        time.sleep(1)



newpid = os.fork()
try:
    if newpid == 0:
        subprocess.call(["php", "-S", "localhost:8000", "index.php"])
    else:
        pollForChanges()
except KeyboardInterrupt:
    os.kill(newpid, signal.SIGTERM)
    print("Bye!")

    