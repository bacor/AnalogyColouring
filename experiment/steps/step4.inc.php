<?php if(isset($ACTIVE) === False) die('Bye bye!'); ?>

<!doctype html>
<html>
  
<head>
    <title><?= $page_title ?></title>
    <link href="style.css" type="text/css" rel="stylesheet" />
</head>

<body>
<form id="experiment-form" method="post">
<div id="wrapper">
    <div id="sidebar">
		<h1><strong>Nearly done!</strong></h1>
        <p>The last thing we would like you to do, is to fill in this survey.
        </p>
        <p>Please answer <em>all</em> the questions (otherwise, we can't use these results).</p>
        <div id="footer">
    	<p>Experimental design by Bas Cornelissen, Iliana Gioulatou, Jori Jansen and Arnold Kochari. For questions, please contact Bas via info at, well, bascornelissen.nl.</p>
    </div>
    </div>

    <div id="experiment-wrapper" class="cf survey">
    	<form></form>
    	<?php
$questions = "
01. I prefer to do things with others rather than on my own.

02. I prefer to do things the same way over and over again.

03. If I try to imagine something, I find it very easy to create a picture in my mind.

04. I frequently get so strongly absorbed in one thing that I lose sight of other things.

05. I often notice small sounds when others do not.

06. I usually notice car number plates or similar strings of information.

07. Other people frequently tell me that what I&rsquo;ve said is impolite, even though I think it is polite.

08. When I&rsquo;m reading a story, I can easily imagine what the characters might look like.

09. I am fascinated by dates.

10. In a social group, I can easily keep track of several different people&rsquo;s conversations.

11. I find social situations easy.

12. I tend to notice details that others do not.

13. I would rather go to a library than to a party.

14. I find making up stories easy.

15. I find myself drawn more strongly to people than to things.

16. I tend to have very strong interests, which I get upset about if I can&rsquo;t pursue.

17. I enjoy social chitchat.

18. When I talk, it isn&rsquo;t always easy for others to get a word in edgewise.

19. I am fascinated by numbers.

20. When I&rsquo;m reading a story, I find it difficult to work out the characters&rsquo; intentions.

21. I don&rsquo;t particularly enjoy reading fiction.

22. I find it hard to make new friends.

23. I notice patterns in things all the time.

24. I would rather go to the theater than to a museum.

25. It does not upset me if my daily routine is disturbed.

26. I frequently find that I don&rsquo;t know how to keep a conversation going.

27. I find it easy to \"read between the lines\" when someone is talking to me.

28. I usually concentrate more on the whole picture, rather than on the small details.

29. I am not very good at remembering phone numbers.

30. I don&rsquo;t usually notice small changes in a situation or a person&rsquo;s appearance.

31. I know how to tell if someone listening to me is getting bored.

32. I find it easy to do more than one thing at once.

33. When I talk on the phone, I&rsquo;m not sure when it&rsquo;s my turn to speak.

34. I enjoy doing things spontaneously.

35. I am often the last to understand the point of a joke.

36. I find it easy to work out what someone is thinking or feeling just by looking at their face.

37. If there is an interruption, I can switch back to what I was doing very quickly.

38. I am good at social chitchat.

39. People often tell me that I keep going on and on about the same thing.

40. When I was young, I used to enjoy playing games involving pretending with other children.

41. I like to collect information about categories of things (e.g., types of cars, birds, trains, plants).

42. I find it difficult to imagine what it would be like to be someone else.

43. I like to carefully plan any activities I participate in.

44. I enjoy social occasions.

45. I find it difficult to work out people&rsquo;s intentions.

46. New situations make me anxious.

47. I enjoy meeting new people.

48. I am a good diplomat.

49. I am not very good at remembering people&rsquo;s date of birth.

50. I find it very easy to play games with children that involve pretending.";
$arr = explode("\n", $questions);
$questions = array();
$options = array('Definitely agree', 'Slightly agree', 'Slightly disagree', 'Definitely disagree');
?> 
<table>
<?php
$i=1;
foreach($arr as $question):
	if($question != ''): 
		$q = $question; //substr($question, 4);
		?>
		
		<?php if($i%5 == 1): ?>
		<tr class="header-row"><td></td>
			<?php foreach($options as $j=>$option): ?>
			<td><?= $option?></td>
			<?php endforeach; ?>
		</tr>
		<?php endif; ?>
		
		<tr>
		<td class="question-column"><?=$q?></td>
		
		<?php foreach($options as $j=>$option): ?>
		<td class="radio-cell">
			<input type="radio" name="question-<?=$i?>" value="<?= $j ?>" />
		</td>
		<?php endforeach; ?>
	
		</tr>
		<?php
		$i++;
	endif;
	
endforeach;
?>
</table>

		<div class="message">
		<h1><strong>And the very last questions</strong></h1>
		<p><label class="desc">Age:</label>
			<input type="text" name="age" /></p>
		<p>
		<label class="desc">Gender:</label>
			<select name="gender">
				<option value="male">Male</option>
				<option value="male">Female</option>
				<option value="other">Other</option>		
			</select></p>
			
		<p><label class="desc">What do you think was the point of this experiment?</label></p>
		<textarea name="goal"></textarea>
		
		<p><label class="desc">Did you experience any difficulties or do you have any questions?</label></p>
		<textarea name="comments"></textarea>
		
    	<input type="hidden" name="subject_id" value="<?= $subject_id; ?>" />
    	<input type="hidden" name="step" value="5" />
    	<input type="hidden" name="trials" value='<?= json_encode($trials); ?>' />
        <input type="submit" class="btn" value="Finish">

		</div>
		
		
    	</div>
    </div>
</div>
 </form>
  	
</body>
</html>