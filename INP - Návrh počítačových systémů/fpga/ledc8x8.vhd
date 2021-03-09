library IEEE;
use IEEE.std_logic_1164.all;
use IEEE.std_logic_arith.all;
use IEEE.std_logic_unsigned.all;

entity ledc8x8 is
port ( 
        ROW		: out std_logic_vector (0 to 7);
		LED		: out std_logic_vector (0 to 7);
		RESET	: in std_logic;
		SMCLK	: in std_logic

);
end ledc8x8;

architecture main of ledc8x8 is

    signal poc : std_logic_vector(11 downto 0) := (others => '0');
    signal zmena_stavu : std_logic_vector(20 downto 0) := (others => '0');
    signal stav : std_logic_vector(1 downto 0) := "00";
    signal riadky : std_logic_vector(7 downto 0) := "11111111";
    signal ledky : std_logic_vector(7 downto 0) := "10000000";
    signal ce : std_logic;
    

begin

    -- Sem doplnte popis obvodu. Doporuceni: pouzivejte zakladni obvodove prvky
    -- (multiplexory, registry, dekodery,...), jejich funkce popisujte pomoci
    -- procesu VHDL a propojeni techto prvku, tj. komunikaci mezi procesy,
    -- realizujte pomoci vnitrnich signalu deklarovanych vyse.

    -- DODRZUJTE ZASADY PSANI SYNTETIZOVATELNEHO VHDL KODU OBVODOVYCH PRVKU,
    -- JEZ JSOU PROBIRANY ZEJMENA NA UVODNICH CVICENI INP A SHRNUTY NA WEBU:
    -- http://merlin.fit.vutbr.cz/FITkit/docs/navody/synth_templates.html.

    -- Nezapomente take doplnit mapovani signalu rozhrani na piny FPGA
    -- v souboru ledc8x8.ucf.
    
    generator_ce: process(SMCLK, RESET)
		begin
			if RESET = '1' then 
				poc <= (others => '0');
			elsif rising_edge(SMCLK) then 
				if poc = "111000010000" then
					ce <= '1';
					poc <= (others => '0');
				else
					ce <= '0';
				end if;
				poc <= poc + 1;
			end if;
		end process generator_ce;


	zmena_stavov: process(SMCLK, RESET) 
		begin
			if RESET = '1' then 
				zmena_stavu <= (others => '0');
			elsif rising_edge(SMCLK) then 
				if zmena_stavu = "111000010000000000000" then
					stav <= stav + 1;
					zmena_stavu <= (others => '0');
					else
						zmena_stavu <= zmena_stavu +1;
				end if;
			end if;
		end process zmena_stavov;


		rotacia: process(RESET, ce, SMCLK)
		begin	
			if RESET = '1' then 
				riadky <= "10000000"; 
			elsif SMCLK'event and SMCLK = '1' and ce = '1' then
				riadky <= riadky(0) & riadky(7 downto 1); 
			end if;
		end process rotacia;


		dekoder: process(riadky)
		begin
		
			if stav = "00" then
			case riadky is
				when "10000000" => ledky <= "11111111";
				when "01000000" => ledky <= "11000111";
				when "00100000" => ledky <= "11011011";
				when "00010000" => ledky <= "11011011";
				when "00001000" => ledky <= "11011011";
				when "00000100" => ledky <= "11011011";
				when "00000010" => ledky <= "11000111";
				when "00000001" => ledky <= "11111111";
				when others =>     ledky <= "11111111";
				end case;
			
			elsif stav = "01" then
			case riadky is
				when "10000000" => ledky <= "11111111";
				when "01000000" => ledky <= "11111111";
				when "00100000" => ledky <= "11111111";
				when "00010000" => ledky <= "11111111";
				when "00001000" => ledky <= "11111111";
				when "00000100" => ledky <= "11111111";
				when "00000010" => ledky <= "11111111";
				when "00000001" => ledky <= "11111111";
				when others =>     ledky <= "11111111";
				end case;
			
			elsif stav = "10" then
			case riadky is
				when "10000000" => ledky <= "11111111";
				when "01000000" => ledky <= "10111101";
				when "00100000" => ledky <= "10111101";
				when "00010000" => ledky <= "10111101";
				when "00001000" => ledky <= "10100101";
				when "00000100" => ledky <= "10011001";
				when "00000010" => ledky <= "10111101";
				when "00000001" => ledky <= "11111111";
				when others =>     ledky <= "11111111";
				end case;
			
			elsif stav = "11" then
			case riadky is
				when "10000000" => ledky <= "11111111";
				when "01000000" => ledky <= "11111111";
				when "00100000" => ledky <= "11111111";
				when "00010000" => ledky <= "11111111";
				when "00001000" => ledky <= "11111111";
				when "00000100" => ledky <= "11111111";
				when "00000010" => ledky <= "11111111";
				when "00000001" => ledky <= "11111111";
				when others =>     ledky <= "11111111";
				end case;
			end if;
		end process dekoder;

		LED <= ledky;
		ROW <= riadky;

end main;
