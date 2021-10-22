from psychopy import core,visual,event
import socket,pickle
import numpy as np

code='utf-8'
c=socket.socket(socket.AF_INET,socket.SOCK_STREAM)
c.connect(('hpc.psy.ntu.edu.tw', 1234))
c.sendall('r09921006'.encode(code)) # Change your ID here!
print("ID received: {}".format(c.recv(9).decode(code))) # Should show your ID!

ACC = np.array([])  # correct or not
RT = np.array([])  # response time
rgb2color = {(1, 0, 0): 'r', (0, 1, 0): 'g', (0, 0, 1): 'b', (1, 1, 0): 'y'}

w=visual.Window(size=[800,400],units='norm')
for t in range(8):
  print("Trial {}:".format(t + 1))
  
  sz=int(c.recv(2).decode(code)) # size
  word=pickle.loads(c.recv(sz))
  print("\tRecieved: {}".format(word))  # return dict {"text": GREEN, "color": (1, 0, 0)}
  
  # Make your changes here (4 points for graphical displaying):
  visual.TextStim(w, text=word["text"], color=word["color"]).draw()
  w.flip()  # show the received word
  
  # Please enter the key corresponding to the color of the text (not the text itself)
  r=event.waitKeys(keyList=['r','g','b','y'],timeStamped=core.Clock())
  print("\tKey response: {}".format(r))
  
  w.flip()  # new blank window
  ACC = np.append(ACC, r[0][0] == rgb2color[word["color"]])  # correct or not
  RT = np.append(RT, r[0][1])  # response time
  
  c.sendall(pickle.dumps(r[0]))

print(c.recv(5).decode(code)) # 4 points for passing
c.close()

print("Accuracy: {} %".format(ACC.mean() * 100))
print("Average Response Time: {} sec.".format(RT.mean()))
