<?php
/* IPP projekt 1 - FIT VUT
 * Zadani: PARSER-IPPCode20
 * Autor: Daniel Weis (xweisd00)
 * Datum: 4.3.2020
 */

//HELP output
if ($argv[1] == "--help" || $argv[1] == "-help") {
    echo "\tphp7.4 parse.php [--help/-help]\n";
    echo "\tSkript typu filtr (parse.php v jazyce PHP 7.4)\n";
    echo "\tnačte ze standardního vstupu zdrojový kód v IPPcode20\n";
    echo "\tzkontroluje lexikální a syntaktickou\n";
    echo "\tsprávnost kódu a vypíše na standardní výstup XML reprezentaci programu.\n";
    exit (0);
}

//opening the memory for xmlwriter functions.
$xw = xmlwriter_open_memory();

//function for writing the XML header.
function write_xml_header($xw){
    xmlwriter_set_indent($xw, 1);
    xmlwriter_set_indent_string($xw, '  ');
    xmlwriter_start_document($xw, '1.0', 'UTF-8');

    xmlwriter_start_element($xw, 'program');
    xmlwriter_start_attribute($xw,'language');
    xmlwriter_text($xw, 'IPPcode20');
    xmlwriter_end_attribute($xw);

}
//function for closing and ending the XML memory and document.
function xml_end($xw){
    xmlwriter_end_element($xw);
    xmlwriter_end_document($xw);
    echo xmlwriter_output_memory($xw);
}
//function that produces XML code from instructions with zero arguments.
function arg_num_0($xw, $ins_order, $ins_name ){

    xmlwriter_start_element($xw, 'instruction');
    xmlwriter_start_attribute($xw, 'order');
    xmlwriter_text($xw, $ins_order);
    xmlwriter_start_attribute($xw,'opcode');
    xmlwriter_text($xw, $ins_name);
    xmlwriter_end_attribute($xw);
    xmlwriter_end_element($xw);

}
//function that produces XML code from instructions with one argument.
function arg_num_1($xw, $ins_order, $ins_name, $ins_type_1, $comment_1 ){

    xmlwriter_start_element($xw, 'instruction');
    xmlwriter_start_attribute($xw, 'order');
    xmlwriter_text($xw, $ins_order);
    xmlwriter_start_attribute($xw,'opcode');
    xmlwriter_text($xw, $ins_name);
    xmlwriter_end_attribute($xw);

    xmlwriter_start_element($xw, 'arg1');
    xmlwriter_start_attribute($xw, 'type');
    xmlwriter_text($xw, $ins_type_1);
    xmlwriter_end_attribute($xw);
    xmlwriter_text ($xw, $comment_1);
    xmlwriter_end_element($xw);
    xmlwriter_end_element($xw);
}
//function that produces XML code from instructions with two argument.
function arg_num_2($xw, $ins_order, $ins_name, $ins_type_1, $ins_type_2, $comment_1, $comment_2){

    xmlwriter_start_element($xw, 'instruction');
    xmlwriter_start_attribute($xw, 'order');
    xmlwriter_text($xw, $ins_order);
    xmlwriter_start_attribute($xw,'opcode');
    xmlwriter_text($xw, $ins_name);
    xmlwriter_end_attribute($xw);

    xmlwriter_start_element($xw, 'arg1');
    xmlwriter_start_attribute($xw, 'type');
    xmlwriter_text($xw, $ins_type_1);
    xmlwriter_end_attribute($xw);
    xmlwriter_text ($xw, $comment_1);
    xmlwriter_end_element($xw);

    xmlwriter_start_element($xw, 'arg2');
    xmlwriter_start_attribute($xw, 'type');
    xmlwriter_text($xw, $ins_type_2);
    xmlwriter_end_attribute($xw);
    xmlwriter_text($xw, $comment_2);
    xmlwriter_end_element($xw);
    xmlwriter_end_element($xw);

}
//function that produces XML code from instructions with three argument.
function arg_num_3($xw, $ins_order, $ins_name, $ins_type_1, $ins_type_2, $ins_type_3, $comment_1, $comment_2, $comment_3){

    xmlwriter_start_element($xw, 'instruction');
    xmlwriter_start_attribute($xw, 'order');
    xmlwriter_text($xw, $ins_order);
    xmlwriter_start_attribute($xw,'opcode');
    xmlwriter_text($xw, $ins_name);
    xmlwriter_end_attribute($xw);

    xmlwriter_start_element($xw, 'arg1');
    xmlwriter_start_attribute($xw, 'type');
    xmlwriter_text($xw, $ins_type_1);
    xmlwriter_end_attribute($xw);
    xmlwriter_text ($xw ,$comment_1);
    xmlwriter_end_element($xw);

    xmlwriter_start_element($xw, 'arg2');
    xmlwriter_start_attribute($xw, 'type');
    xmlwriter_text($xw, $ins_type_2);
    xmlwriter_end_attribute($xw);
    xmlwriter_text ($xw ,$comment_2);
    xmlwriter_end_element($xw);

    xmlwriter_start_element($xw, 'arg3');
    xmlwriter_start_attribute($xw, 'type');
    xmlwriter_text($xw, $ins_type_3);
    xmlwriter_end_attribute($xw);
    xmlwriter_text ($xw ,$comment_3);
    xmlwriter_end_element($xw);
    xmlwriter_end_element($xw);
}
//function that checks the correct types of instrument type with REGEX functions, then returns the correct output.
function is_it_right($input){
    if (preg_match('/(GF|LF|TF)@(_|\-|\$|&|%|\*|\!|\?|)(([a-zA-Z]+)|\d|[_\-$&%*!?])*/', $input)){        //checking if it is VAR argument
        $output = "var";
    }
    elseif (preg_match('/(int)@(_|\-|\$|&|%|\*|\!|\?|[a-zA-Z0-9])*/', $input)){         //checking if it is INT argument
        $output = "int";
    }
    elseif(preg_match('/(bool)@(true|false)/', $input)){                                //checking if it is BOOL argument
        $output = "bool";
    }
    elseif (preg_match('/string@(\\\d{3,}|[^\\\\\s^#])*/', $input)){                    //checking if it is STRING argument
        $output = "string";
    }
    elseif (preg_match('/(nil)@(nil)/', $input)){                                       //checking if it is NIL argument
        $output = "nil";
    }
    elseif (preg_match('/(int|bool|string|nil|label|type|var)/', $input)){              //checking if it is TYPE argument
        $output = "type";
    }
    elseif (preg_match('/^[[:alpha:]_\-$&%*][[:alnum:]_\-$&%*]*$/', $input)){           //checking if it is LABEL argument
        $output = "label";
    }
    else{
        exit(23);
    }
    return $output;

}
//function that returns the correct values from instruction type TYPE.
function symbol_instruction_comment($input){
    if (preg_match('/(string|int|bool|nil)/', $input)){
        $output = explode('@', $input);
        return $output[1];
    }
    return $input;
}

//loading in the first line and deleting all characters after # (because 'comments')
//and checking if there is the language definition.
$first_line = fgets(STDIN);
$first_line = preg_replace('/\s+/', '', $first_line);
if (strpos($first_line, '#') == true) {
    $first_line = str_replace(substr($first_line, strpos($first_line, '#')), '', $first_line);
}

if(strtolower($first_line) != ".ippcode20") {
    fwrite(STDERR, "Missing language definition (.IPPcode20). \n");
    exit (21);
}

//calling the xml header function
write_xml_header($xw);

$i = 0;
//while loop for loading in from STDIN then checking the incoming code threw switch
// and calling the xml functions in switch cases.
while ($lines = fgets(STDIN)) {
    if (strpos($lines, '#') == true) {
        $lines = str_replace(substr($lines, strpos($lines, '#')), '', $lines);
    }

    $lines = preg_replace('/[\s\t]+/', ' ', $lines);
    $lines = trim($lines);
    $items = explode(' ', $lines);
    if($items[0] == ""){
        continue;
    }
    //print_r($items);
    $i++;
    switch (strtoupper($items[0])) {

        #instructions with 0 param
        case "CREATEFRAME":
        case "PUSHFRAME":
        case "POPFRAME":
        case "RETURN":
        case "BREAK":
            arg_num_0($xw, $i, strtoupper($items[0]));
            break;
        #instructions with 1 param:
        case "DEFVAR":
        case "POPS":
        case "CALL":
        case "LABEL":
        case "JUMP":
        case "PUSHS":
        case "WRITE":
        case "EXIT":
        case "DPRINT":
            arg_num_1($xw, $i, strtoupper($items[0]), is_it_right($items[1]), symbol_instruction_comment($items[1]));
            break;
        #instructions with 2 param:
        case "MOVE":
        case "INT2CHAR":
        case "READ":
        case "STRLEN":
        case "TYPE":
            arg_num_2($xw, $i, strtoupper($items[0]), is_it_right($items[1]), is_it_right($items[2]), symbol_instruction_comment($items[1]), symbol_instruction_comment($items[2]) );
            break;
        #instructions with 3 param:
        case "ADD":
        case "SUB":
        case "MUL":
        case "IDIV":
        case "LT":
        case "GT":
        case "EQ":
        case "AND":
        case "OR":
        case "NOT":
        case "STRI2INT":
        case "CONCAT":
        case "GETCHAR":
        case "SETCHAR":
        case "JUMPIFEQ":
        case "JUMPIFNEQ":
            arg_num_3($xw, $i, strtoupper($items[0]), is_it_right($items[1]), is_it_right($items[2]), is_it_right($items[3]),symbol_instruction_comment($items[1]), symbol_instruction_comment($items[2]), symbol_instruction_comment($items[3]));
            break;

        default:
            exit (22);
    }

}
//caling the end of XML document and printing out the XML code.
xml_end($xw);
