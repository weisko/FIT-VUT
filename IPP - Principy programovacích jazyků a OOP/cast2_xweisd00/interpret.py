import xml.etree.cElementTree as ET 
import optparse
import sys
import os
import re
import warnings

warnings.filterwarnings("ignore", category=DeprecationWarning)
#variable initialization class
class C_Variables_Init:
	var_type = None
	value = None
	op = ''

	def __init__(self, arg):
		self.op = arg

#symbol class with functions over variable types
class C_Symb:
	line_cnt = 0

	#Init checking
	def initalization_check(first, second):
		if ((first == 'nil' or first == None) or (second == 'nil' or second == None)):
				print("ERROR the variable is not initialized", file=sys.stderr)
				sys.exit(56)

	#when exit instruction is set performes actions
	def exit_inst(xmlPart):
		if xmlPart.getchildren()[0].get('type') == 'var':
			inputs = C_Vars.var_frame_check(((xmlPart.getchildren())[0].text)[3:], (xmlPart.getchildren())[0].text[:3])
			if inputs.var_type != 'int':
				print("ERROR variable value is not valid", file=sys.stderr)
				sys.exit(56)
			inputs = inputs.value
		if (xmlPart.getchildren()[0].get('type') != 'int' and xmlPart.getchildren()[0].get('type') != 'var'):
			print("ERROR symb value is not valid", file=sys.stderr)
			sys.exit(53)
		else:
			inputs = (xmlPart.getchildren())[0].text
		if (not inputs.isdigit() or not (int(inputs) <= 49 and int(inputs) >=0)):
			print("ERROR symb value is not valid", file=sys.stderr)
			sys.exit(57)
		sys.exit(int(inputs))

	#checker for symbol types and value
	def check_symb(argX, symb_type):
		try:
			if (argX != 'string' and argX != 'bool' and  argX != 'nil' and argX != 'int' ):
				return False
			if argX == 'int':
				if not symb_type:
					return True
				if all(y in "0123456789+-" for y in symb_type):
					return True
				raise 
			elif argX == 'nil':
				if (symb_type == 'nil'):
					return True
				raise
			elif argX == 'bool':
				if (symb_type == 'true' or symb_type == 'false'):
					return True
				raise
			elif argX == 'string':
				if not symb_type:
					return True
				if re.search(r'[#\s]', symb_type):
					raise
				return True
			else:
				return False
		except Exception:
			print("ERROR symb format is wrong", file=sys.stderr)
			sys.exit(32)

	#coverter for string esc sequnece
	def esc_string_convert(str):
		try:
			if str == None:
				return ''
			i = 0
			helper = ''
			while i < len(str):
				if str[i] == '\\':
					esc = int(0)
					counter = int(100)
					for x in range(0,3):
						i = i + 1
						if str[i].isdigit():
							esc = esc + counter * int(str[i])
							counter = counter / int(10)
						else: 
							raise 
					helper = helper + chr(int(esc))
				elif (str[i] != '#' and not str[i].isspace()):
					helper = helper + str[i]
				else:
					raise
				i = i + 1
			str = str
			return str
		except Exception:
			print("ERROR symb format is wrong", file=sys.stderr)
			sys.exit(32)
				
class C_Vars:

	#definiton of variable and storing it in frame
	def var_definition(xmlPart):
		if (xmlPart.getchildren())[0].get('type') != 'var':
			print("ERROR argument type is wrong!", file=sys.stderr)
			sys.exit(32)
		var = ((xmlPart.getchildren())[0].text)[3:]
		C_Vars.var_checker(var, ((xmlPart.getchildren())[0].text)[:3])	
		C_Frame_types.var_store_in_frame(xmlPart,var)
	
	#fucntion checks if the var is in the frame if true returns it
	def var_frame_check(var, in_frame):
		if in_frame == 'LF@':
			if C_Frame_types.frames_cnter < 1:
				print("ERROR LF does not exist", file=sys.stderr)
				sys.exit(55)
			if not var in C_Frame_types.frame_local[C_Frame_types.frames_cnter-1]:
				print("ERROR variable does not exist", file=sys.stderr)
				sys.exit(54)
			return C_Frame_types.frame_local[C_Frame_types.frames_cnter-1][var]
		if in_frame == 'GF@':
			if not var in C_Frame_types.frame_global:
				print("ERROR variable does not exist", file=sys.stderr)
				sys.exit(54)
			return C_Frame_types.frame_global[var]
		if not C_Frame_types.frame_checker(in_frame):
			print("ERROR frame type is unknown", file=sys.stderr)
			sys.exit(32)
		else:
			if var not in C_Frame_types.frame_temporary:
				print("ERROR variable does not exist", file=sys.stderr)
				sys.exit(54)
			if C_Frame_types.checker == False:
				print("ERROR TF is not active", file=sys.stderr)
				sys.exit(55)
			return C_Frame_types.frame_temporary[var]

	#checking of variable format
	def var_checker(var, in_frame):
		character = var[0]
		if not C_Frame_types.frame_checker(in_frame):
			print("ERROR frame type is wrong", file=sys.stderr) 
			sys.exit(32)
		if (character == '?' or character == '_' or character == '-' or character == '*' or character == '%' or character == '!' or character == '$' or character == '&' or character.isalpha() == True):
			for c in var:
				if (c != '?' and c != '_' and c != '-' and c != '*' and c != '%' and c != '!' and c != '$' and c != '&' and c.isalnum() == False):
					print("ERROR variable format is wrong", file=sys.stderr)
					sys.exit(32)
		else:
			print("ERROR variable format is wrong", file=sys.stderr)
			sys.exit(32)
	

#Class for labels
class C_Label:
	action = []
	lbl = {}

	#checker for label format
	def label_checker(is_label,label):
		character = label[0]
		if is_label != 'label':
			print("ERROR argument type is wrong", file=sys.stderr)
			sys.exit(32)
		if (character == '?' or character == '_' or character == '-' or character == '*' or character == '%' or character == '!' or character == '$' or character == '&' or  character.isalpha() == True):
			for c in label:
				if (c != '?' and c != '_' and c != '-' and c != '*' and c != '%' and c != '!' and c != '$' and c != '&' and c.isalnum() == False):
					print("ERROR label format is wrong", file=sys.stderr)
					sys.exit(32)
		else:
			print("ERROR label format is wrong", file=sys.stderr)
			sys.exit(32)
	#creation of label and checking if a label already exists
	def label_creator_checker(xmlPart, insturction_num):
		label = (xmlPart.getchildren())[0].text
		C_Label.label_checker(xmlPart.getchildren()[0].get('type'), label)
		if (label in C_Label.lbl):
				print("ERROR the label already exists", file=sys.stderr)
				sys.exit(52)
		C_Label.lbl[label] = insturction_num

	#creating label for every LABEL in xml
	def labels_creation(instructions):
		for i in range(len(instr)):
			if(instr[i].get('opcode', i) == 'LABEL'):
				C_Label.label_creator_checker(instr[i], i)
	
	def label_call(xmlPart, insturction_num):
		label = (xmlPart.getchildren())[0].text

		C_Label.label_checker(xmlPart.getchildren()[0].get('type'), label)
		if (label not in C_Label.lbl):
			print("ERROR the label does not exist", file=sys.stderr)
			sys.exit(52)

		C_Label.action.append(insturction_num)
		return(C_Label.lbl[label])

	#jump on labels and returning ins number
	def label_jump(is_label, op):
		C_Label.label_checker(is_label, op)
		if (op not in C_Label.lbl):
			print("ERROR the label does not exist", file=sys.stderr)
			sys.exit(52)
		return(C_Label.lbl[op])

	#return from label handler
	def out_from_label():
		if len(C_Label.action) == 0:
			print("ERROR no label to return to", file=sys.stderr)
			sys.exit(56)

#Frames class
class C_Frame_types:
	stack = []
	frame_local = []
	frame_temporary = {}
	frame_global = {}
	checker = False
	frames_cnter = 0

	#storing variable in the frame
	def var_store_in_frame(xmlPart, var):
		if ((xmlPart.getchildren())[0].text)[:3] == 'GF@':
			C_Frame_types.frame_global[var] = C_Variables_Init(var)

		elif ((xmlPart.getchildren())[0].text)[:3] == 'LF@':
			if C_Frame_types.frames_cnter < 1:
				print("ERROR localF does not exist", file=sys.stderr)
				sys.exit(55)
			C_Frame_types.frame_local[C_Frame_types.frames_cnter-1][var] = C_Variables_Init(var)

		elif ((xmlPart.getchildren())[0].text)[:3] == 'TF@':
			if C_Frame_types.checker == False:
				print("ERROR temporaryF does not exist", file=sys.stderr)
				sys.exit(55)
			C_Frame_types.frame_temporary[var] = C_Variables_Init(var)
		else:
			print("ERROR wrong frame type", file=sys.stderr)
			sys.exit(32)

	#pop of variables
	def var_pop(xmlPart):
		if (xmlPart.getchildren())[0].get('type') != 'var':
			print("ERROR argument type is wrong", file=sys.stderr)
			sys.exit(32)
		if not C_Frame_types.stack:
			print("ERROR stack is empty", file=sys.stderr)
			sys.exit(56)
		in_frame = ((xmlPart.getchildren())[0].text)[:3]
		var = ((xmlPart.getchildren())[0].text)[3:]
		C_Vars.var_checker(var,in_frame)
		inputs = C_Vars.var_frame_check(var, in_frame)
		pushed_data = C_Frame_types.stack.pop()
		if in_frame == 'GF@':
			C_Frame_types.frame_global[var].var_type = pushed_data.var_type
			C_Frame_types.frame_global[var].value = pushed_data.value

		elif in_frame == 'LF@':
			C_Frame_types.frame_local[C_Frame_types.frames_cnter-1][var].var_type = pushed_data.var_type
			C_Frame_types.frame_local[C_Frame_types.frames_cnter-1][var].value = pushed_data.value 

		else:
			C_Frame_types.frame_temporary[var].var_type = pushed_data.var_type
			C_Frame_types.frame_temporary[var].value = pushed_data.value

	#checker for frame type
	def frame_checker(in_frame):
		if (in_frame == 'GF@' or in_frame == 'TF@' or in_frame == 'LF@'):
			return True
		else:
			return False

	#pushing of frames
	def frame_pusher():
		if C_Frame_types.checker == False:
			print("Error - TF not created!", file=sys.stderr)
			sys.exit(55)
		C_Frame_types.frames_cnter += 1
		C_Frame_types.frame_local.append({})
		C_Frame_types.frame_local[C_Frame_types.frames_cnter-1] = C_Frame_types.frame_temporary.copy()
		C_Frame_types.frame_temporary.clear()
		C_Frame_types.checker = False

	#pop frame handler if there is no frame to pop error
	def frame_poper():
		if C_Frame_types.frames_cnter < 1:
			print("ERROR there is no frame to pop", file=sys.stderr)
			sys.exit(55)

		C_Frame_types.checker = True
		C_Frame_types.frames_cnter -= 1
		C_Frame_types.frame_temporary = C_Frame_types.frame_local.pop()

#ins checker retunr of number of arguments
def instruction_codes(op, arguments_number):
	if (op == 'RETURN' or op == 'BREAK' or op == 'PUSHFRAME' or op == 'POPFRAME' or op == 'CREATEFRAME'):
		if arguments_number > 0:
			print("ERROR argument count is wrong", file=sys.stderr)
			sys.exit(32)
		return arguments_number
	if (op == 'CALL' or op == 'POPS' or op == 'EXIT' or op == 'WRITE' or op == 'JUMP' or op == 'PUSHS' or op == 'DEFVAR' or op == 'DPRINT' or op == 'LABEL'):
		if arguments_number != 1:
			print("ERROR argument count is wrong", file=sys.stderr)
			sys.exit(32)
		return arguments_number
	if (op == 'INT2CHAR' or op == 'MOVE' or op == 'TYPE' or op == 'NOT' or op == 'STRLEN' or op == 'READ'):
		if arguments_number != 2:
			print("ERROR argument count is wrong", file=sys.stderr)
			sys.exit(32)
		return arguments_number
	if (op == 'OR' or op == 'ADD' or op == 'MUL' or op == 'IDIV' or op == 'LT' or op == 'GT' or op == 'SETCHAR' or op == 'EQ' or op == 'AND' or op == 'SUB' or op == 'JUMPIFNEQ' or op == 'STRI2INT' or op == 'CONCAT' or op == 'GETCHAR' or op == 'JUMPIFEQ'):
		if arguments_number != 3:
			print("ERROR argument count is wrong", file=sys.stderr)
			sys.exit(32)
		return arguments_number
	print("ERROR operation code is wrong", file=sys.stderr)
	sys.exit(32)

#Intrepretation of instructions by number of arguments
def parsing_of_instructions(op, xmlPart, C_Frame_types, C_Label, i, input_data):
	global instruction_end 
	instruction_end += 1
	arguments_number = instruction_codes(op, len(list(xmlPart)))
	if arguments_number == 0:
		if op == 'CREATEFRAME':
			C_Frame_types.checker = True
			C_Frame_types.frame_temporary.clear()
		elif op == 'POPFRAME':
			C_Frame_types.frame_poper()
		elif op == 'PUSHFRAME':
			C_Frame_types.frame_pusher()
		elif op == 'RETURN':
			C_Label.out_from_label()
			return C_Label.action.pop()
		elif op == 'BREAK':
			print("GF:", C_Frame_types.frame_global,'\nLF:', C_Frame_types.frame_local, '\nTF:', C_Frame_types.frame_temporary, '\nInstructions done:', instruction_end, file=sys.stderr)

	#ONE argument insractions		
	elif arguments_number == 1:	
		if xmlPart.getchildren()[0].tag != 'arg1':
			print("ERROR wrong XML format", file=sys.stderr)
			sys.exit(32)
		if op == 'PUSHS':
			arg_type = (xmlPart.getchildren())[0].get('type')
			if arg_type == 'var':
				var = ((xmlPart.getchildren())[0].text)[3:]; in_frame = ((xmlPart.getchildren())[0].text)[:3]; C_Vars.var_checker(var, in_frame); inputs = C_Vars.var_frame_check(var, in_frame)
				if inputs.value == None or inputs.value == 'nil':
					print("ERROR var has no value", file=sys.stderr)
					sys.exit(56)
				C_Frame_types.stack.append(inputs)
			elif C_Symb.check_symb(arg_type, (xmlPart.getchildren())[0].text):
				if arg_type == 'nil':
					print("Error - non valid value!", file=sys.stderr)
					sys.exit(56)
				if arg_type == 'string':
					(xmlPart.getchildren())[0].text = C_Symb.esc_string_convert((xmlPart.getchildren())[0].text)
				inputs = C_Variables_Init(''); inputs.var_type = (xmlPart.getchildren())[0].get('type'); inputs.value = (xmlPart.getchildren())[0].text; C_Frame_types.stack.append(inputs)
			else:	
				print("ERROR wrong type of operand", file=sys.stderr)
				sys.exit(32)
		elif op == 'POPS':
			C_Frame_types.var_pop(xmlPart)	
		elif op == 'CALL':
			return(C_Label.label_call(xmlPart, i))
		elif op == 'DEFVAR':
			if C_Frame_types.frame_checker(((xmlPart.getchildren())[0].text)[:3]):
				C_Vars.var_definition(xmlPart)
			else:
				print("ERROR wrong frame type", file=sys.stderr)
				sys.exit(32)
		elif op == 'WRITE':
			arg_type = (xmlPart.getchildren())[0].get('type')
			if arg_type == 'var':
				var = ((xmlPart.getchildren())[0].text)[3:]; in_frame = ((xmlPart.getchildren())[0].text)[:3]; C_Vars.var_checker(var, in_frame); inputs = C_Vars.var_frame_check(var, in_frame)
				if inputs.value == None:
					print("ERROR var is not initialized", file=sys.stderr)
					sys.exit(56)
				if inputs.value != 'nil':
					print(inputs.value, end='')
			elif C_Symb.check_symb(arg_type, (xmlPart.getchildren())[0].text):
				(xmlPart.getchildren())[0].text = C_Symb.esc_string_convert((xmlPart.getchildren())[0].text)
				print((xmlPart.getchildren())[0].text, end='')
			else:	
				print("ERROR operand type is wrong", file=sys.stderr)
				sys.exit(32)
		elif op == 'DPRINT':
			argument_type1 = (xmlPart.getchildren())[0].get('type')
			if argument_type1 == 'var':
				var = (xmlPart.getchildren())[0].text[3:]; in_frame = (xmlPart.getchildren())[0].text[:3]; C_Vars.var_checker(var, in_frame); inputs = C_Vars.var_frame_check(var, in_frame)
				if inputs.value == None:
					print("Error - not initialized var!", file=sys.stderr)
					sys.exit(56)
				if inputs.value != 'nil':
					print(inputs.value, file=sys.stderr)
			elif C_Symb.check_symb(argument_type1, (xmlPart.getchildren())[0].text):
				print((xmlPart.getchildren())[0].text, file=sys.stderr)
			else:
				print("ERROR operand type is wrong", file=sys.stderr)
				sys.exit(32)
		elif op == 'EXIT':
			C_Symb.exit_inst(xmlPart)
		elif op == 'JUMP':
			return(C_Label.label_jump((xmlPart.getchildren())[0].get('type'), (xmlPart.getchildren())[0].text))
		
	#TWO argument instructions
	elif arguments_number == 2:
		if (xmlPart.getchildren()[0].tag == 'arg1' and xmlPart.getchildren()[1].tag == 'arg2'):
			argument_n1 = xmlPart.getchildren()[0]; argument_n2 = xmlPart.getchildren()[1]
		elif (xmlPart.getchildren()[0].tag == 'arg2' and xmlPart.getchildren()[1].tag == 'arg1'):
			argument_n1 = xmlPart.getchildren()[1]; argument_n2 = xmlPart.getchildren()[0]
		else:
			print("ERROR wrong XML format", file=sys.stderr)
			sys.exit(32)
		argument_type1 = argument_n1.get('type');argument_type2 = argument_n2.get('type')
		if argument_type1 == 'var':
			variable_n1 = argument_n1.text[3:]; frame_n1 = argument_n1.text[:3]; C_Vars.var_checker(variable_n1, frame_n1);destination_copy = C_Vars.var_frame_check(variable_n1, frame_n1)
		else:
			print("ERROR operand type is wrong", file=sys.stderr)
			sys.exit(32)
		if op == 'MOVE':
			if argument_type2 == 'var':
				variable_n2 = argument_n2.text[3:]; frame_n2 = argument_n2.text[:3]; C_Vars.var_checker(variable_n2, frame_n2); source_copy = C_Vars.var_frame_check(variable_n2, frame_n2); destination_copy.value = source_copy.value; destination_copy.var_type = source_copy.var_type
			elif C_Symb.check_symb(argument_type2, argument_n2.text):
					if argument_type2 == 'string':
						argument_n2.text = C_Symb.esc_string_convert(argument_n2.text)
					destination_copy.value = argument_n2.text; destination_copy.var_type = argument_type2
			else:
				print("ERROR operand type is wrong", file=sys.stderr)
				sys.exit(32)
		elif op == 'NOT':
			try:
				negation = ''
				if argument_type2 == 'var':
					variable_n2 = argument_n2.text[3:]; frame_n2 = argument_n2.text[:3]; C_Vars.var_checker(variable_n2, frame_n2);source_copy = C_Vars.var_frame_check(variable_n2, frame_n2)
					if source_copy.var_type != 'bool':
						raise
					negation = source_copy.value
				elif C_Symb.check_symb(argument_type2, argument_n2.text) and argument_type2 == 'bool':
					negation = argument_n2.text
				else:
					raise
				if negation == 'true':
					destination_copy.value = 'false'
				else:
					destination_copy.value = 'true'; destination_copy.var_type = 'bool'
			except Exception:
				print("ERROR operand type is wrong", file=sys.stderr)
				sys.exit(32)
		elif op == 'READ':
			type_name = argument_n2.text
			if (argument_type2 != 'type' or (type_name != 'int' and type_name != 'bool' and type_name != 'string')):
				print("ERROR operand type is wrong", file=sys.stderr)
				sys.exit(32)
			if not source_in:
				inputs = input()
			else:
				inputs = input_data[C_Symb.line_cnt][:-1]
				C_Symb.line_cnt += 1
			if type_name == 'int':
				if all(y in "0123456789+-" for y in inputs):
					destination_copy.value = inputs
				else:
					destination_copy.value = 0
				destination_copy.var_type = 'int'
			elif type_name == 'bool':
				if inputs.lower() == 'true':
					destination_copy.value = inputs.lower()
				else:
					destination_copy.value = 'false'
				destination_copy.var_type = 'bool'
			elif type_name == 'string':
				if re.search(r'[#\s]', inputs):
					destination_copy.value = ''
				else:
					destination_copy.value = inputs
				destination_copy.var_type = 'string'
		elif op == 'INT2CHAR' or op == 'STRLEN' or op == 'TYPE':
			try:
				inputs = ''
				inputs_type = ''
				if argument_type2 == 'var':
					variable_n2 = argument_n2.text[3:]; frame_n2 = argument_n2.text[:3]; C_Vars.var_checker(variable_n2, frame_n2); source_copy = C_Vars.var_frame_check(variable_n2, frame_n2)
					if (op == 'STRLEN' and source_copy.var_type != 'string'):
						raise
					inputs = source_copy.value; inputs_type = source_copy.var_type
				elif C_Symb.check_symb(argument_type2, argument_n2.text):
					if (op == 'STRLEN' and argument_type2 != 'string'):
						raise
					if argument_type2 == 'string':
						argument_n2.text = C_Symb.esc_string_convert(argument_n2.text)
					inputs = argument_n2.text; inputs_type = argument_type2
				else:
					raise
				if op == 'INT2CHAR':
					try:
						if inputs_type != 'int':
							print("ERROR int value is not valid", file=sys.stderr)
							sys.exit(53)
						destination_copy.value = chr(int(inputs))
						destination_copy.var_type = 'string'
					except Exception:
						print("ERROR ord value is not valid", file=sys.stderr)
						sys.exit(58)
				elif op == 'STRLEN':
					destination_copy.value = len(inputs)
					destination_copy.var_type = 'int'
				else:
					if inputs_type == None:
						destination_copy.value = ''
					else:
						destination_copy.value = inputs_type
					destination_copy.var_type = 'string'
			except Exception:
				print("ERROR operand type is wrong", file=sys.stderr)
				sys.exit(56)
		

	#THREE argument instructions
	else:
		argument_n1 = ''
		argument_n2 = ''
		argument_n3 = ''
		for x in range(0,3):
			if xmlPart.getchildren()[x].tag == ('arg1'):
				argument_n1 = xmlPart.getchildren()[x]
			elif xmlPart.getchildren()[x].tag == ('arg2'):
				argument_n2 = xmlPart.getchildren()[x]
			elif xmlPart.getchildren()[x].tag == ('arg3'):
				argument_n3 = xmlPart.getchildren()[x]
		if argument_n1 == '' or argument_n2 == '' or argument_n3 == '':
			print("ERROR wrong XML format", file=sys.stderr)
			sys.exit(32)
		argument_type1 = argument_n1.get('type'); argument_type2 = argument_n2.get('type'); argument_type3 = argument_n3.get('type')
		if argument_type2 == 'var':
			variable_n2 = argument_n2.text[3:]; frame_n2 = argument_n2.text[:3]; C_Vars.var_checker(variable_n2, frame_n2); symbol_n1 = C_Vars.var_frame_check(variable_n2, frame_n2); type_of_symbol_n1 = symbol_n1.var_type; value_of_symbol_n1 = symbol_n1.value
		elif C_Symb.check_symb(argument_type2, argument_n2.text):
			if argument_type2 == 'string':
				argument_n2.text = C_Symb.esc_string_convert(argument_n2.text)
			type_of_symbol_n1 = argument_type2; value_of_symbol_n1 = argument_n2.text
		else:
			print("ERROR operand type is wrong", file=sys.stderr)
			sys.exit(32)
		if argument_type3 == 'var':
			variable_n3 = argument_n3.text[3:]; frame_n3 = argument_n3.text[:3]; C_Vars.var_checker(variable_n3, frame_n3); symbol_n2 = C_Vars.var_frame_check(variable_n3, frame_n3); type_of_symbol_n2 = symbol_n2.var_type; value_of_symbol_n2 = symbol_n2.value
		elif C_Symb.check_symb(argument_type3, argument_n3.text):
			if argument_type3 == 'string':
				argument_n3.text = C_Symb.esc_string_convert(argument_n3.text)
			type_of_symbol_n2 = argument_type3; value_of_symbol_n2 = argument_n3.text
		else:
			print("ERROR operand type is wrong", file=sys.stderr)
			sys.exit(32)
		if op == 'JUMPIFNEQ' or op == 'JUMPIFEQ':
			if type_of_symbol_n1 != type_of_symbol_n2:
				print("Error - wrong operand types!", file=sys.stderr)
				sys.exit(53)
			if ((value_of_symbol_n1 == value_of_symbol_n2 and op == 'JUMPIFEQ') or (value_of_symbol_n1 != value_of_symbol_n2 and op == 'JUMPIFNEQ')):
				return(C_Label.label_jump(argument_type1, argument_n1.text))
		else:
			if argument_type1 == 'var':
				variable_n1 = argument_n1.text[3:]; frame_n1 = argument_n1.text[:3]; C_Vars.var_checker(variable_n1, frame_n1); destination_copy = C_Vars.var_frame_check(variable_n1, frame_n1)
			else:
				print("ERROR operand type is wrong", file=sys.stderr)
				sys.exit(32)
			if op == 'ADD' or op == 'SUB' or op == 'MUL' or op == 'IDIV':
				if (type_of_symbol_n1 != 'int' or type_of_symbol_n2 != 'int'):
					print("ERROR operand type is wrong", file=sys.stderr)
					sys.exit(53)
				if op == 'SUB':
					destination_copy.value = int(value_of_symbol_n1) - int(value_of_symbol_n2)
				elif op == 'MUL':
					destination_copy.value = int(value_of_symbol_n1) * int(value_of_symbol_n2)
				elif op == 'ADD':
					destination_copy.value = int(value_of_symbol_n1) + int(value_of_symbol_n2)
				elif op == 'IDIV':
					if int(value_of_symbol_n2) == 0:
						print("ERROR cannot divide by zero", file=sys.stderr)
						sys.exit(57)
					destination_copy.value = int(value_of_symbol_n1) // int(value_of_symbol_n2)
				destination_copy.var_type = 'int'
			elif op == 'AND' or op == 'OR':
				if (type_of_symbol_n1 != 'bool' or type_of_symbol_n2 != 'bool' ):
					print("ERROR operand type is wrong", file=sys.stderr)
					sys.exit(53)
				if op == 'AND':
					if value_of_symbol_n1 == 'true' and value_of_symbol_n2 == 'true':
						destination_copy.value = 'true'
					else:
						destination_copy.value = 'false'
				else:
					if value_of_symbol_n1 == 'true' or value_of_symbol_n2 == 'true':
						destination_copy.value = 'true'
					else:
						destination_copy.value = 'false'
				destination_copy.var_type = 'bool'
			elif op == 'LT' or op == 'GT' or op == 'EQ':
				if (type_of_symbol_n1 != type_of_symbol_n2):
					if op == 'EQ' and (type_of_symbol_n1 == 'nil' or type_of_symbol_n2 == 'nil'):
						pass
					else:
						print("Error - types are not same!", file=sys.stderr)
						sys.exit(53)
				if type_of_symbol_n1 == 'nil' and op != 'EQ':
					print("Error - nil is not allowed!", file=sys.stderr)
					sys.exit(53)
				if op == 'GT':
					if type_of_symbol_n1 == 'int':
						if int(value_of_symbol_n1) > int(value_of_symbol_n2):
							destination_copy.value = 'true'
						else:
							destination_copy.value = 'false'
					elif type_of_symbol_n1 == 'bool':
						if value_of_symbol_n1 == 'true' and value_of_symbol_n2 == 'false':
							destination_copy.value = 'true'
						else:
							destination_copy.value = 'false'
					else:
						if value_of_symbol_n1 > value_of_symbol_n2:
							destination_copy.value = 'true'
						else:
							destination_copy.value = 'false'
				elif op == 'LT':
					if type_of_symbol_n1 == 'int':
						if int(value_of_symbol_n1) < int(value_of_symbol_n2):
							destination_copy.value = 'true'
						else:
							destination_copy.value = 'false'
					elif type_of_symbol_n1 == 'bool':
						if value_of_symbol_n1 == 'false' and value_of_symbol_n2 == 'true':
							destination_copy.value = 'true'
						else:
							destination_copy.value = 'false'
					else:
						if value_of_symbol_n1 < value_of_symbol_n2:
							destination_copy.value = 'true'
						else:
							destination_copy.value = 'false'
				else:
					if value_of_symbol_n1 == value_of_symbol_n2:
						destination_copy.value = 'true'
					else:
						destination_copy.value = 'false'
				destination_copy.var_type = 'bool'
			elif op == 'CONCAT':
				C_Symb.initalization_check(type_of_symbol_n1, type_of_symbol_n2)
				if (type_of_symbol_n1 != type_of_symbol_n2 or type_of_symbol_n1 != 'string'):
					print("ERROR operand type is wrong", file=sys.stderr)
					sys.exit(53)
				destination_copy.value = value_of_symbol_n1 + value_of_symbol_n2; destination_copy.var_type = 'string'
			elif op == 'STRI2INT':
				if (type_of_symbol_n1 != 'string' or type_of_symbol_n2 != 'int'):
					print("ERROR operand type is wrong", file=sys.stderr)
					sys.exit(53)
				try:
					destination_copy.value = ord(value_of_symbol_n1[int(value_of_symbol_n2)]); destination_copy.var_type = 'int'
				except Exception:
					print("Error - wrong index!", file=sys.stderr)
					sys.exit(58)
			elif op == 'SETCHAR':
				if (type_of_symbol_n1 != 'int' or type_of_symbol_n2 != 'string'):
					print("ERROR operand type is wrong", file=sys.stderr)
					sys.exit(53)
				try:
					if int(value_of_symbol_n1) >= len(destination_copy.value) or int(value_of_symbol_n1) < 0:
						raise
					destination_copy.value = destination_copy.value[:int(value_of_symbol_n1)] + value_of_symbol_n2[0] + destination_copy.value[int(value_of_symbol_n1)+1:]; destination_copy.var_type = 'string'
				except Exception:
					print("Error - wrong index!", file=sys.stderr)
					sys.exit(58)
			elif op == 'GETCHAR':
				C_Symb.initalization_check(type_of_symbol_n1, type_of_symbol_n2)
				if (type_of_symbol_n1 != 'string' or type_of_symbol_n2 != 'int'):
					print("ERROR operand type is wrong", file=sys.stderr)
					sys.exit(53)
				try:
					destination_copy.value = value_of_symbol_n1[int(value_of_symbol_n2)]; destination_copy.var_type = 'string'
				except Exception:
					print("ERROR index is wrong", file=sys.stderr)
					sys.exit(58)
	return i

arg_parser = optparse.OptionParser()
arg_parser.add_option('--source', dest="xmlfile", help="input file with XML code")
arg_parser.add_option('--input', dest="intfile", help="input file for interpret")

try:
	options,args = arg_parser.parse_args()
except Exception:
	print("ERROR wrong parameters", file=sys.stderr)
	sys.exit(10)

if options.xmlfile and os.path.getsize(options.xmlfile) == 0:
	sys.exit(0)

#
source = options.xmlfile
source_in = options.intfile

if not source and not source_in:
	print("ERROR no parameter", file=sys.stderr)
	sys.exit(10)

if not source:
	source = sys.stdin
if source_in:
	try:
		fp = open(source_in)
		input_data = fp.readlines()
	except Exception:
		print("ERROR with opening the file", file=sys.stderr)
		sys.exit(11)
	finally:
		fp.close()
else:
	input_data = ''
try:
	tree = ET.parse(source)
except Exception:
	print("ERROR wrong XML format", file=sys.stderr)
	sys.exit(31)

#roots from xml file and ordering
xml_roots = tree.getroot(); xml_root_tag = xml_roots.tag
xml_roots[:] = sorted(xml_roots, key = lambda child: (child.tag, int(child.get('order'))))

#calling of creation of all labels and call functions for interpret
try:
	instr = tree.findall('.//instruction')
	C_Label.labels_creation(instr)
	instruction_end = 0
	a = 0
	while a < len(instr):
		if int(instr[a].get('order')) != a + 1:
			raise
		actInst = instr[a].get('opcode', a)
		if not actInst:
			print("ERROR wrong XML format", file=sys.stderr)
			sys.exit(31)
		a = parsing_of_instructions(actInst, instr[a], C_Frame_types, C_Label, a, input_data)+1
except Exception:
	print("ERROR wrong XML format", file=sys.stderr)
	sys.exit(32)

try:
	if (xml_root_tag != "program" or 
		xml_roots.attrib["language"] != "IPPcode20" or
		len(xml_roots.attrib) > 3 or (len(xml_roots.attrib) == 2 and ('name' not in xml_roots.attrib and 'description' not in xml_roots.attrib)) or (len(xml_roots.attrib) == 3 and ('name' not in xml_roots.attrib or 'description' not in xml_roots.attrib))):
		print("ERROR wrong XML format", file=sys.stderr)
		sys.exit(31)
except Exception:
	print("ERROR wrong XML format", file=sys.stderr)
	sys.exit(31)

