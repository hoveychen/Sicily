from zipfile import ZipFile
import json
import sys
import re
import os

template = u'''<html>
<head>
    <title>%(title)s</title>
</head>
<body>
<center>
<h1>%(title)s</h1>
<p>Time Limit: %(time_limit)ssec, Memory Limit: %(memory_limit)s KB</p>
<p>Special Judge: %(special_judge)s, Framework Judge: %(has_framework)s </p>
</center>
<hr />
<h2>Testdata</h2>
<table border = 1 >
<tr><th>Input</th><th>Output</th></tr>
%(testdata_tbl)s
</table>
<h2>Description</h2>
<p>%(description)s</p>
<h2>Input</h2>
<p>%(input)s</p>
<h2>Output</h2>
<p>%(output)s</p>
<h2>Sample Input</h2>
<pre>%(sample_input)s</pre>
<h2>Sample Output</h2>
<pre>%(sample_output)s</pre>
<h2>Hints</h2>
<p>%(hint)s</p>
</body>
</html>
'''

img_re = re.compile(r'%IMGPATH%([^/<\'"]*)')

def process_problem(filepath):
    with ZipFile(filepath, 'r') as myzip:
        data = json.loads(myzip.read('metadata.json'))
        testdata_tbl = ''
        mypath = data['title']
        for line in myzip.read('.DIR').split('\n'):
            files = line.split()
            if len(files) != 2: continue
            input_file = files[0]
            output_file = files[1]
            testdata_tbl += ('<tr><td><a href="{2}/{0}">{0}</a></td><td>'\
                            '<a href="{2}/{1}">{1}</a></td></tr>')\
                            .format(input_file.encode('utf-8'), output_file.encode('utf-8'), mypath.encode('utf-8'))
            myzip.extract(input_file, mypath)
            myzip.extract(output_file, mypath)
            
        data['testdata_tbl'] = testdata_tbl.decode('utf-8')
        html_path = data['title'] + ".html"
        with open(html_path, "wb") as file:
            data['special_judge'] = {'0': 'No', '1': 'Yes'} [data['special_judge']]
            data['has_framework'] = {'0': 'No', '1': 'Yes'} [data['has_framework']]
            def repl(matchobj):
                myzip.extract(matchobj.group(1), mypath)
                return mypath.encode('utf-8') + u"/" + matchobj.group(1)
            for key in ['description', 'input', 'output', 'hint']:
                data[key] = img_re.sub(repl, data[key])
            content = template%data
            file.write(content.encode('utf-8'))
    return html_path

def extract_problems(filename):
    with ZipFile(filename, 'r') as myzip:
        data = json.loads(myzip.read('metadata.json'))
        index_tbl = "<ul>"
        for pid in data:
            problem_path = pid + '.zip'
            myzip.extract(problem_path)
            html_path = process_problem(problem_path)
            os.remove(problem_path)
            index_tbl += '<li><a href="%s">%s</a></li>' % (html_path, html_path[:-5])
        index_tbl += '</ul>'
        open('index.html', 'wb').write(index_tbl.encode('utf-8'))
        os.system('index.html')
        
def extract_problem(filename):
    html_path = process_problem(filename)
    os.system('"' + html_path + '"')

if __name__ == '__main__':
    success = 0
    for filepath in sys.argv[1:]:
        namelist = ZipFile(filepath, 'r').namelist()
        if '.DIR' in namelist:
            extract_problem(filepath)
            success = 1
        elif '.SET' in namelist:
            extract_problems(filepath)
            success = 1

    if success:
        print 'Done'
    else:
        print '''No Sicily archive file found
Usage:
$ python pviewer.py file1.zip [file2.zip, [file3.zip... ]]'''
