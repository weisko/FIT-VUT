file=proj2

pdf:
	latex ${file}.tex
	latex ${file}.tex
	dvips -t a4 ${file}.dvi
	ps2pdf ${file}.ps

clean:
	rm ${file}.dvi
	rm ${file}.ps
	rm ${file}.pdf
