import sys
import os
from fabric.api import local

def cmd(cmd=""):
    ''' Run a command in the site directory. Usable from other commands. '''
    require('site_path')
    
    if not cmd:
        sys.stdout.write(_cyan("Command to run: "))
        cmd = raw_input().strip()
        
    local(cmd)
    
def ssh():
    ''' Forward localhost:27017 to secretariat (mongoDB)
        Forward localhost:6379 to secretariat (redis)
    '''
    sys.stdout.write("Username for secretariat: ")
    username = raw_input().strip()
    local("ssh -f -NL 27017:localhost:27017 " + username + "@10.200.76.95")
    
def runserver():
    ''' Run python manage.py runserver [::]:8000 '''
    os.chdir(os.path.join(os.pardir, "ncs-heatmap"))
    local('python tstream.py')
    local('python tweet_service.py')
