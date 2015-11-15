import pandas as pd
import numpy as np
import json
from IPython.display import Image
from IPython.display import display
from IPython.core.display import HTML
from matplotlib._pylab_helpers import Gcf
from IPython.core.pylabtools import print_figure
from base64 import b64encode
import matplotlib.pyplot as plt
import brewer2mpl

def loadData(
			trials='trials-preprocessed.json', 
			survey='survey-preprocessed.json',
			objNames='object-names.json',
			dir='data/'):
	"""Loads all data of the experiment. 
	"""
	
	trials = pd.read_json( dir + trials ).set_index('trial_id')
	survey = pd.read_json( dir + survey ).set_index('survey_id')
	with open( dir + objNames) as file:
	    objNames = json.load(file)

	trial_types = [
	    ['KA1', 'KA2'],
	    ['KB1', 'KB2'],
	    ['KC1', 'KC2'],
	    ['KD1', 'KD2'],
	    ['MHA1', 'MHA2'],
	    ['MHB1', 'MHB2'],
	    ['MHC1', 'MHC2'],
	    ['MHD1', 'MHD2']]

	return (trials, survey, objNames, trial_types)

def codeToggler():
	return HTML("""
		<script>
			code_show=false; 
			function code_toggle(){ 
				if (code_show){
					$('div.input').hide();
				} else {
					$('div.input').show();
				}; 
				code_show = !code_show;
			}; 
			$( document ).ready(code_toggle);
		</script>
		<button onclick=\"code_toggle()\">Toggle code</button>
		""")


def getPictureSrc(name):
    src = 'data/drawings/'
    if name[:1] == 'M':
        src += name[:2]+'-'+name[2]+'-'+name[3]
    else:
        src += name[0]+'-'+name[1]+'-'+name[2]
    src += '.png'
    return src


def showPicture(name, width=200):
	"""Shows a picture """
	display(Image(filename = getPictureSrc(name), width=width))
    
def showPictures(*args):
	"""Shows multiple pictures"""
	for name in args:
		showPicture(name)

def analyzeTrial(instruction, test, df, objNames):

	# Colors
	bmap = brewer2mpl.get_map('Set2', 'qualitative', len(objNames[instruction]), reverse=True)
	colors = bmap.mpl_colors + [(.75,.75,.75)]
	                          
	p = getAnalogyLikelihoods(instruction, test, df, objNames)
	p.plot(kind='barh', stacked=True, figsize=(5, .4 * len(p)), color=colors)
	legend = plt.legend(bbox_to_anchor=(1.05,1.05), loc='upper left')
	legend.get_frame().set_color('white')

	plt.savefig('exports/correspondence-prob-'+test+'.png', format='png', dpi=300,
	    bbox_extra_artists=(legend,), bbox_inches='tight')

	# Nice output
	# http://stackoverflow.com/questions/28877752/ipython-notebook-how-to-combine-html-output-and-matplotlib-figures
	fig = Gcf.get_all_fig_managers()[-1].canvas.figure
	image_data = "data:image/png;base64,%s" % b64encode(print_figure(fig)).decode("utf-8")
	Gcf.destroy_fig(fig)

	html = """
	        <table style="border-color:#ccc; margin-bottom:20px; border-bottom:3px solid #333">
	            <tr>
	                <td style="border-color:#ccc">
	                    Instruction (%s):
	                    <img src='%s' width='250'/>
	                </td style="border-color:#ccc">
	                <td rowspan="2" style="border-color:#ccc">
	                    <img src="%s" />
	                </td>
	            </tr>
	            <tr>
	                <td style="border-color:#ccc">
	                    Test (%s):
	                    <img src='%s'  width='250'/>
	                </td>
	            </tr>
	        </table>"""
	display(HTML(html % (instruction, getPictureSrc(instruction), image_data, test, getPictureSrc(test))))


def getAnalogyLikelihoods(instruction, test, df, objNames):
	"""
	Calculates, for every object in the test image and every
	object in the instruction image, the probability that they
	correspond to each other. Symbolically, it is the matrix
	$\Bigr[p(A_u(t_i) = u_j)\Bigl]_{i,j}$

	Rows correspond to objects in the test image,
	Columns correspond to objects in the instruction image
	"""
	data = df[df['trial_type'] == instruction[:-1]]

	# Also show indices:
	#     testObjNames = [n+' ('+str(i)+')' for i, n in enumerate(objNames[test])];
	#     instrObjNames =[n+' ('+str(i)+')' for i, n in enumerate(objNames[instruction])];
	testObjNames = objNames[test]
	instrObjNames = objNames[instruction]

	analogies = pd.DataFrame(list(data['analogies']), index = data.index, columns = testObjNames)

	# All possible instruction objects
	instructionObjects = pd.Series(analogies.values.ravel()).unique()
	index = []
	results = []

	# Loop over all objects in the test image and for every 
	# object in the instruction, calculate the probability
	# that it has the same color.
	for testObjDesc in analogies.columns:
		index += [testObjDesc]
		row = {}
		for instrObjId in instructionObjects:
			s = analogies[testObjDesc]
			try:
				part = len(s[s == instrObjId]) + .0
			except TypeError: 
				part = 0.0

			if(instrObjId == ''):
				key = 'None'
			else:
				key = instrObjNames[instrObjId]
			p = part / len(s)
			row[key] = p
		results += [row]

	likelihood = pd.DataFrame(results, index=index)
	likelihood = likelihood[instrObjNames + ['None']] # Sort columns
	return likelihood


def correspondenceProbabilities(instruction, test, df, objNames):
	"""
	Calculates, for every object in the test image and every
	object in the instruction image, the probability that they
	correspond to each other. Symbolically, it is the matrix
	$\\bigr[p_i(u_j)]_{i,j}\\bigr]$

	Rows correspond to objects in the test image,
	Columns correspond to objects in the instruction image
	"""
	return getAnalogyLikelihoods(instruction, test, df, objNames)

def correspondenceEntropies(instruction, test, df, objNames):
	"""
	For every object in the test images, this calculates the entropy
	of the corresponding distribution over objects in the instruction image.
	This is the average disagreement about the correspondence.
	"""
	p = correspondenceProbabilities(instruction, test, df, objNames);
	return (p * -np.log(p)).fillna(0).sum(axis=1);

def averageEntropy(instruction, test, df, objNames):
	"""
	Returns the average entropy of an entire analogy, so it averages over
	the entropies of the correspondences in the analogy.
	"""
	return correspondenceEntropies(instruction, test, df, objNames).mean()

def analogyScore(analogy, surprisals):
	"""
	Calculates the score for a given analogy in some trial
	$s(t) = \frac{1}{n} \sum_{i=1}^n -\log(p_i(c(t_i)))$.

	As second input it takes a matrix $S$ with the surprisal 
	for every pair (t_i, u_j). That is, $S(i,j) = -\log()
	"""
	s = 0;
	for t_i, u_j in enumerate(analogy):
		if(u_j == ''):
			u_j = -1 # 'None' is the last column
		s += surprisals.iloc[t_i, u_j]
	return s / len(analogy)


def AQPoints(selections):
	"""Calculates the points for a set of selections on the AQ test"""
	points    = []
	agrees    = [1, 2, 4, 5, 6, 7, 9, 12, 13, 16, 18, 19, 20, 21, 22, 23, 26, 33, 35, 39, 41, 42, 43, 45, 46]
	disagrees = [3, 8, 10, 11, 14, 15, 17, 24, 25, 27, 28, 29, 30, 31, 32, 34, 36, 37, 38, 40, 44, 47, 48, 49, 50]

	for i, selection in enumerate(selections):
		if (i+1 in agrees) and (selection in [0, 1]):     # 0/1 --> definitely/slightly agree
			points += [1]
		elif (i+1 in disagrees) and (selection in [2,3]): # 2/3 --> slightly/definitely disagree
			points += [1]
		else:
			points += [0]
		    
	return points


def AQSubscaleScores(points):
	"""
	Calculates the scores for AQ subsets
	"""
	social = [1,11,13,15,22,36,44,45,47,48]
	attention_switching = [2,4,10,16,25,32,34,37,43,46]
	detail = [5,6,9,12,19,23,28,29,30,49]
	communication = [7,17,18,26,27,31,33,35,38,39] 
	imagination = [3,8,14,20,21,24,40,41,42,50]

	return {
		'social': sum([points[i] for i in social]),
		'attention_switching': sum([points[i] for i in attention_switching]),
		'detail': sum([points[i] for i in detail]),
		'communication': sum([points[i] for i in communication]),
		'imagination': sum([points[i] for i in imagination]),
	}
