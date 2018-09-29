# -*- coding: utf-8 -*-

import sublime
import sublime_plugin
import os
import logging
import datetime


class FileLog(sublime_plugin.EventListener):
	# create logger
	def __init__(self):
		print("FileLog: init")

		today = datetime.date.today();
		home = os.path.expanduser("~");
		folder_path = home + '/sublime-filelog/' + today.strftime("%Y/%m")
		self.file_path = folder_path + '/data.log'
		if not os.path.exists(folder_path):
			os.makedirs(folder_path)

		self.logger = logging.getLogger(__name__)
		self.logger.setLevel(logging.INFO)
		self.formatter = logging.Formatter("%(asctime)s --- %(message)s")
		self.handler_info = logging.FileHandler(self.file_path, mode="a", encoding="utf-8")

		if not self.logger.handlers:
			self.logger.addHandler(self.handler_info)

		self.handler_info.setFormatter(self.formatter)

		self.modification = 0

		print(self);

	def log_setup():
		self.log_handler = logging.handlers.WatchedFileHandler(self.file_path)

	def on_modified(self, view):
		self.modification += 1;
		# print("mod : " + str(self.modification))

	def log_line(self, view, action):
		mod =  ""
		if(self.modification > 0 and action == "save"):
			mod = " : " + str(self.modification) + " actions"

		filename = view.file_name()
		message = action + " --- " + filename + mod
		self.logger.info(message)

		self.modification = 0;

	def on_pre_save_async(self, view):
		print("FileLog: save")
		self.log_line(view,"save")

	def on_load(self, view):
		print("FileLog: load")
		self.log_line(view,"load")
