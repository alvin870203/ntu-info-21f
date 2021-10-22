import numpy as np,glob as gb,socket,pickle,_thread,time

code='utf-8'; 
Ss=[{"text":'RED',"color":(1,0,0)},{"text":'GREEN',"color":(0,1,0)},
    {"text":'BLUE',"color":(0,0,1)},{"text":'YELLOW',"color":(1,1,0)},
    {"text":'RED',"color":(0,1,0)},{"text":'GREEN',"color":(1,0,0)},
    {"text":'BLUE',"color":(1,1,0)},{"text":'YELLOW',"color":(0,0,1)}] #stimuli

As=['r','g','b','y','g','r','y','b'] # correct answer

Cs=[0,0,0,0,1,1,1,1]


def on_new_client(c,addr):
 #np.random.seed(time.time())
 idx=np.random.permutation(range(8))
 RTs=[0,0]; ACCs=[0,0];
 ID=c.recv(9).decode(code)
 print("ID from "+str(addr))
 c.sendall(ID.encode(code)) # echo back ID
 for t in idx: # trial
   cond=Cs[t]
   word=pickle.dumps(Ss[t])
   sz=str(len(word)).encode(code)
   c.sendall(sz)
   c.sendall(word)
   r=pickle.loads(c.recv(25)) # get a trial response from the client
   RTs[cond]+=r[1]
   if(r[0]==As[t]): #correct
    ACCs[cond]+=1
 for cond in range(2):
    ACCs[cond]=ACCs[cond]/4.0
    RTs[cond]=RTs[cond]/4.0
 ACC=(ACCs[0]+ACCs[1])/2.0
 if(ACC>0.85):
  c.sendall('Pass!'.encode(code))
  print(ID+" pass:",ACCs+RTs)
  with open('02_results.csv', 'a') as f:
   f.write(ID+","+(','.join(str(e) for e in ACCs+RTs)+'\n'))
 else:
  c.sendall('Fail!'.encode(code))
  print(ID+" fail:",ACCs+RTs)
 c.close()

s=socket.socket(socket.AF_INET,socket.SOCK_STREAM)
s.setsockopt(socket.SOL_SOCKET,socket.SO_REUSEADDR,1)

host = socket.gethostname() # Get local machine name
port = 1234                # Reserve a port for your service.

print('Server started!')
print('Waiting for clients...')

s.bind(('140.112.62.11', 1234))
s.listen(4096)                 # Now wait for client connection.

while True:
   c, addr = s.accept()     # Establish connection with client.
   _thread.start_new_thread(on_new_client,(c,addr))
   
s.close()
