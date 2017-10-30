import sublime
import sublime_plugin
import os
import logging
import datetime

today = datetime.date.today();

home = os.path.expanduser("~");
folder_path = home + '/sublime-filelog/' + today.strftime("%Y/%m")
file_path = folder_path + '/data.log'

if not os.path.exists(folder_path):
	os.makedirs(folder_path)

def log_setup():
	log_handler = logging.handlers.WatchedFileHandler(file_path)
logger = logging.getLogger(__name__)
logger.setLevel(logging.INFO)
formatter = logging.Formatter("%(asctime)s --- %(message)s")
handler_info = logging.FileHandler(file_path, mode="a", encoding="utf-8")

if not logger.handlers:
	print("OK")
	logger.addHandler(handler_info)

handler_info.setFormatter(formatter)

class FileLog(sublime_plugin.EventListener):
	# create logger
	def __init__(self):
		print("FileLog: init")
		self.modification = 0

	def on_modified(self, view):
		self.modification += 1;
		print("mod : " + str(self.modification))

	def log_line(self,view,action):
		mod =  ""
		if(self.modification > 0 and action == "save"):
			mod = " : " + str(self.modification) + " actions"

		filename = view.file_name()
		message = action + " --- " + filename + mod
		logger.info(message)

		self.modification = 0;

	def on_pre_save_async(self,view):
		print("FileLog: save")
		self.log_line(view,"save")

	def on_load(self,view):
		print("FileLog: load")
		self.log_line(view,"load")
