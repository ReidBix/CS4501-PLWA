<html lang = "en">

<style>
.correct {
	color: red;
    text-transform: uppercase;
}
.partial {
	color: blue;
    text-transform: uppercase;
}
.wrong {
	color: black;
    text-transform: lowercase;
}

table{
    table-layout: fixed;
    width: 40px;
    height: 40px;

}

th, td {
    overflow: hidden;
    width: 40px;
    height: 40px;
}
table {
		border-collapse: collapse;
	}
</style>

<head>
	<title> Start Page </title>
	<script>
	    var start = Date.now();
	    var time = document.getElementById('time');
	    var freeze = false;

	    /*
	    Loads initial page, sets necessary messages
	    */
		function begin(){
			//Hide necessary forms
			document.getElementById("loginForm").style.visibility="visible";
			document.getElementById("roundForm").style.visibility="hidden";
			document.getElementById("table").style.display="none";

			//Stuff
			makeMsg();
			ajaxWords();
			if (sessionStorage.getItem['name'] === null){

			}
			document.getElementById('welcome').innerHTML = "Welcome to Lingo!";
			document.getElementById("roundNum").innerHTML="";
			document.getElementById('txt').innerHTML = "";
		}

		/*
		Creates message for display
		*/
		function makeMsg(){
			if (sessionStorage.getItem('msg') === null) {
				var msg = "Welcome to start.php!";
				sessionStorage.setItem('msg', msg);
			} else {
				var msg = sessionStorage.getItem(['msg']);
			}
			document.getElementById('msg').textContent = msg;
		}

		/*
		Creates name local storage variable and proceeds to getRound();
		*/
		function loginClick(){
 			var name = document.getElementById('loginName').value;
 			sessionStorage.setItem('name', name);
	    	document.getElementById('loginName').value = "";
	    	document.getElementById('welcome').innerHTML = "Welcome to Lingo, " + name + "!";
	    	var rem = document.getElementById("loginForm");
	    	rem.parentNode.removeChild(rem);
			document.getElementById("roundForm").style.visibility="visible";
			getRound();
		}

		/*
		Gets the round number and sets the current round as well as creates button to start
		*/
		function getRound(){
			if (sessionStorage.getItem('round') === null) {
				var round = 0;
			} else {
				var round = parseInt(sessionStorage.getItem(['round']));
			}
			round += 1;
			sessionStorage.setItem('round', round);
			document.getElementById('roundNum').textContent = "Round " + round;
			document.getElementById('roundButton').value = "Start Round " + round + "!";
		}


		/*
		Initiates the start of a round, with the first puzzle
		*/
		function startRound(){
			var rem = document.getElementById("roundForm");
	    	rem.parentNode.removeChild(rem);
			var e = document.getElementById("end1");
		    if (e != null){
		    	e.parentNode.removeChild(e);
		    }
			startPuzzle();
		}

		/*
		Add an item from the guess box to the guesses array, which is populated with all of the users guesses
		*/
		function enterClick(){
			if (sessionStorage.getItem('guesses') === null) {
				var guesses = new Array();
				sessionStorage.setItem('guesses', JSON.stringify(guesses));
			} else {
				var guesses = JSON.parse(sessionStorage.getItem(['guesses']));
			}
		    var guess = document.getElementById('guess').value;
	    	document.getElementById('guess').value = "";
	    	guesses.push(guess);
	    	sessionStorage.setItem('guesses', JSON.stringify(guesses));
	    	start=Date.now();
	    	var curWord = sessionStorage.getItem(['curWord']);
	    	var gNum = guesses.length;
	    	// Use guess!
	    	checkGuess(curWord, guess, gNum);
		}

		/*
		Gets the current puzzle/word from the local storage
		*/
		function getPuzzle(){
			var words = JSON.parse(sessionStorage.getItem(['words']));
			if (sessionStorage.getItem('puzzle') === null) {
				var puzzle = 0;
			} else {
				var puzzle = parseInt(sessionStorage.getItem(['puzzle']));
			}
			puzzle += 1;
			sessionStorage.setItem('puzzle', puzzle);
			if (puzzle == 1){
				var word = words[0];
			}
			if (puzzle == 2){
				var word = words[1];
			}
			if (puzzle == 3){
				var word = words[2];
			}
			if (puzzle == 4){
				var word = words[3];
			}
			if (puzzle == 5){
				var word = words[4];
			}
			sessionStorage.setItem('curWord', word);

			if (sessionStorage.getItem('numP') === null) {
				var numP = 0;
			} else {
				var numP = parseInt(sessionStorage.getItem(['numP']));
			}
			numP += 1;
			sessionStorage.setItem('numP', numP);
		}
 		
 		/*
 		Timer function for counting down from 10
 		*/
		function timer(){
			if (!freeze) {
				var r = document.getElementById('r');
				var diff = Date.now() - start, ns = (((11000-diff)/1000)>>0), m = (ns/60) >> 0, s = ns-m*60;
				r.textContent = s;
				if(diff>(11000)){
					start=Date.now();
					var blank = setTimeout(enterClick,1);
				}
				setTimeout(timer,1);
			}
		}

		/*
		AJAX Request function for getting the 5 random words
		*/
		function ajaxWords(){
            var ajaxRequest;
            try {
				ajaxRequest = new XMLHttpRequest();
			} catch (e) {
            	try {
  	        		ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
              	} catch (e) {
                	try{
                		ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
                 	}catch (e){
          				alert("Your browser broke!");
  						return false;
       				}
        		}
     		}	
     		ajaxRequest.onreadystatechange = function(){
            	if(ajaxRequest.readyState == 4){
                    var ajaxDisplay = document.getElementById('w');
                   	var words = JSON.parse(ajaxRequest.responseText);
					var words = new Array(words[0],words[1],words[2],words[3],words[4]);
					sessionStorage.setItem('words', JSON.stringify(words));
					alert(words.toString());
                }
            }
           	ajaxRequest.open("GET", "getWords.php", true);
            ajaxRequest.send(null); 
        }

		/*
 		Checks the users guess with the current puzzle word
 		*/
		function checkGuess(curWord, guess, gNum){
			if (curWord == guess){
				//Correct!
				if (sessionStorage.getItem('right') === null) {
					var right = 0;
				} else {
					var right = parseInt(sessionStorage.getItem(['right']));
				}
				right += 1;
				sessionStorage.setItem('right', right);
				if (sessionStorage.getItem('numR') === null) {
					var numR = 0;
				} else {
					var numR = parseInt(sessionStorage.getItem(['numR']));
				}
				numR += 1;
				sessionStorage.setItem('numR', numR);
				if (right == 3){
					var msg="<h4 id=\"loss\">Congrats, you won this round! Play again!<\/h4>";
					nextRound(msg);
					return;
				}
				var msg="<h4 id=\"loss\">Congrats, you won! The word was <font id=\"lossTxt\" class=\"correct\">" + curWord + "<\/font><\/h4>";
				nextPuzzle(msg);
			}
			else if (gNum >= 5) {
				//End puzzle
				var puzzle = parseInt(sessionStorage.getItem(['puzzle']));
				if (puzzle+1 == 6){
					var msg =" <h4 id=\"loss\">Sorry, you lost this round. Play again!<\/h4>";
					nextRound(msg);
					return;
				}
				var msg="<h4 id=\"loss\">Sorry, you lost. The word was <font id=\"lossTxt\" class=\"correct\">" + curWord + "<\/font><\/h4>";
				nextPuzzle(msg)
			} else {
				var cell;
				var associativeArray = getFrequency(curWord);
				//Fill in correct letters
				for (i = 0; i < curWord.length; i++) {
					cell = gNum.toString() + (i+1).toString();
					if (curWord[i] == guess[i]) {
						associativeArray[curWord[i]] -= 1;
						document.getElementById(cell).className = "correct";
					}
				}

				//Fill in partial letters
				for (i = 0; i < curWord.length; i++){
					for (j = 0; j < 5; j++){
						cell = gNum.toString() + (j+1).toString();
						if (curWord[i] == guess[j]){
							if (associativeArray[curWord[i]] > 0){
								associativeArray[curWord[i]] -= 1;
								document.getElementById(cell).className = "partial";
							}
						}
					}
				}

				//Fill in wrong and blanks
				for (i = 0; i < curWord.length; i++) {
					cell = gNum.toString() + (i+1).toString();
					if (guess[i] == null){
						document.getElementById(cell).textContent = "-";
					} else {
						document.getElementById(cell).textContent = guess[i];
					}					
				}				
			}
		}

		function getFrequency(string) {
		    var freq = {};
		    for (var i=0; i<string.length;i++) {
		        var character = string.charAt(i);
		        if (freq[character]) {
		           freq[character]++;
		        } else {
		           freq[character] = 1;
		        }
	    	}
    		return freq;
		}

		function nextPuzzle(msg){
			freeze = true;
			var rem1 = document.getElementById("r");
	    	rem1.parentNode.removeChild(rem1);
			var rem = document.getElementById("guessForm");
	    	rem.parentNode.removeChild(rem);
 			document.getElementById("table").style.display="none";
 			var f = document.createElement("form");
 			f.setAttribute('id',"puzForm");
			var s = document.createElement("input");
			s.setAttribute('type',"button");
			s.setAttribute('id',"puzButton");
			s.setAttribute('onclick',"startPuzzle();");
			s.setAttribute('value','Next Puzzle!');
			f.setAttribute('align','center');
			f.appendChild(s);
			var txt = document.getElementById('txt');
			txt.parentNode.insertBefore(f, txt);
			txt.innerHTML = msg;
		}

		function startPuzzle(){
			getPuzzle();

			start = Date.now();
			freeze = false;
  			timer();
  			var rem = document.getElementById("puzForm");
  			if (rem != null){
  				rem.parentNode.removeChild(rem);
  			}
 			document.getElementById("table").style.display="table";
 			var f = document.createElement("form");
 			f.setAttribute('id','guessForm');
 			f.setAttribute('onsubmit','return false');
 			var t = document.createElement("input");
 			t.setAttribute('type','text');
 			t.setAttribute('id','guess');
 			t.required = true;
			var s = document.createElement("input");
			s.setAttribute('type',"button");
			s.setAttribute('id',"enter");
			s.setAttribute('onclick',"enterClick();");
			s.setAttribute('value','Enter');
			f.setAttribute('align','center');
			f.appendChild(t);
			f.appendChild(s);
			var d = document.createElement("div");
			d.setAttribute('id','r');
			var tab = document.getElementById('table');
			tab.parentNode.insertBefore(d, tab);
			var tim = document.getElementById('r');
			tim.parentNode.insertBefore(f, tim);
			document.getElementById('txt').innerHTML = "";
			var guesses = new Array();
			sessionStorage.setItem('guesses', JSON.stringify(guesses));
			for (i = 0; i < 5; i++){
				for (j = 0; j < 5; j++){
					cell = (i+1).toString() + (j+1).toString();
					document.getElementById(cell).className = "wrong";
					document.getElementById(cell).textContent = "";
				}
			}
 	    	var curWord = sessionStorage.getItem(['curWord']);
 			document.getElementById("01").textContent = curWord[0];
		}

		function nextRound(msg){
			var rou = sessionStorage.getItem('round');
			var numP = parseInt(sessionStorage.getItem(['numP']));
			var numR = parseInt(sessionStorage.getItem(['numR']));
			var p = document.createElement("p");
			p.setAttribute('id','end1');
			var t1 = document.createTextNode("Stats: ");
			p.appendChild(t1);
			p.appendChild(document.createElement("br"));
			var t2 = document.createTextNode("Total Rounds Played: " + rou);
			p.appendChild(t2);  
			p.appendChild(document.createElement("br"));
			var t3 = document.createTextNode("Total Puzzles Played: " + numP);
			p.appendChild(t3);  
			p.appendChild(document.createElement("br"));
			var t4 = document.createTextNode("Total Puzzles Won: " + numR);
			p.appendChild(t4);
			p.appendChild(document.createElement("br"));
			var end = document.getElementById('end');
			end.parentNode.insertBefore(p, end);
			ajaxWords();
			var txt = document.getElementById('txt');
			txt.innerHTML = msg;
			freeze = true;
			var rem1 = document.getElementById("r");
	    	rem1.parentNode.removeChild(rem1);
			var rem = document.getElementById("guessForm");
	    	rem.parentNode.removeChild(rem);
 			document.getElementById("table").style.display="none";
			var f = document.createElement("form");
 			f.setAttribute('id',"roundForm");
			var s = document.createElement("input");
			s.setAttribute('type',"button");
			s.setAttribute('id',"roundButton");
			s.setAttribute('onclick',"startRound();");
			s.setAttribute('value','');
			f.setAttribute('align','center');
			f.appendChild(s);
			var txt = document.getElementById('txt');
			txt.parentNode.insertBefore(f, txt);
			txt.innerHTML = msg;
			getRound();
			sessionStorage.setItem('puzzle', 0);
			sessionStorage.setItem('right', 0);
		}
	</script>
</head>

<body onload = "begin();">

<center>
	<p id="msg"> C </p>
	<h1 id="welcome"> D </h1>
	<h2 id="roundNum"> E </h2>

	<form id="loginForm" onsubmit = "return false">
			<h3 id="nameQ">What's your name?</h3>
			<input type='text' id='loginName' required/>
			<input type = "button" id = "login" onclick = "loginClick();" value = "Enter"/>
	</form>

	<form id="roundForm" onsubmit = "return false" method = 'post'>
			<input type='submit' id="roundButton" onclick = "startRound();" value='' />
	</form>

	<div id="r"></div>

	<table border = "1" width = "30%" height = "30%" id="table">
	<tr align = "center">
		<td> <p id="01" class="correct"> </p> </td>	
		<td> <p id="02" class=""> </p> </td>	
		<td> <p id="03" class=""> </p> </td>	
		<td> <p id="04" class=""> </p> </td>	
		<td> <p id="05" class=""> </p> </td>
	</tr>
	<tr align = "center">
		<td> <p id="11" class="wrong"> </p> </td>	
		<td> <p id="12" class="wrong"> </p> </td>	
		<td> <p id="13" class="wrong"> </p> </td>	
		<td> <p id="14" class="wrong"> </p> </td>	
		<td> <p id="15" class="wrong"> </p> </td>	
	</tr>
	<tr align = "center">
		<td> <p id="21" class="wrong"> </p> </td>	
		<td> <p id="22" class="wrong"> </p> </td>	
		<td> <p id="23" class="wrong"> </p> </td>	
		<td> <p id="24" class="wrong"> </p> </td>	
		<td> <p id="25" class="wrong"> </p> </td>
	</tr>
	<tr align = "center">
		<td> <p id="31" class="wrong"> </p> </td>	
		<td> <p id="32" class="wrong"> </p> </td>	
		<td> <p id="33" class="wrong"> </p> </td>	
		<td> <p id="34" class="wrong"> </p> </td>	
		<td> <p id="35" class="wrong"> </p> </td>
	</tr>
	<tr align = "center">
		<td> <p id="41" class="wrong"> </p> </td>	
		<td> <p id="42" class="wrong"> </p> </td>	
		<td> <p id="43" class="wrong"> </p> </td>	
		<td> <p id="44" class="wrong"> </p> </td>	
		<td> <p id="45" class="wrong"> </p> </td>
	</tr>
	<tr align = "center">
		<td> <p id="51" class="wrong"> </p> </td>	
		<td> <p id="52" class="wrong"> </p> </td>	
		<td> <p id="53" class="wrong"> </p> </td>	
		<td> <p id="54" class="wrong"> </p> </td>	
		<td> <p id="55" class="wrong"> </p> </td>
	</tr>
	</table>
	<h3 id="txt"></h3>
	<div id="end"></div>
		</center>
	</body>
</html>


