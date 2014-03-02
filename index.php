<!DOCTYPE html>
<!-- 
Author : Sylvain Boissel
Date : 2013-02-05
Desc : Un générateur de grilles de « bullshit bingo »
License : WTFPL 2.0 http://www.wtfpl.net/
(If you fork/reuse this code, no credits needed but much appreciated !)

Resources :
- Twitter Bootstrap : http://twitter.github.com/bootstrap/ (Apache license v2)
- Font Awesowme Icons pack  : http://fortawesome.github.com/Font-Awesome/ (MIT license)
- Light paper fibers Background by Jorge Fuentes : http://subtlepatterns.com/light-paper-fibers/ (CC-By-SA 3.0)
 -->
<html>
  <head>
    <title>jQBingo</title>
    <meta charset="utf-8">
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="css/font-awesome.min.css" rel="stylesheet" media="screen">
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link href='css/style.css' rel='stylesheet' type='text/css'>
  </head>
  <body>
	<div class="navbar navbar-inverse navbar-fixed-top">
		  <div class="navbar-inner">
				<div class="container">
				  <span class="subtitle"></span>
				  <a class="brand" href="http://ashtree.eu/bingo/"><span class="title">jQBingo</span></a>
				  <div class="nav-collapse collapse"></div>
				</div>
		  </div>
	</div>
	
    <div class="container" id="wrapper">
		<noscript>
			<div class="alert alert-error">
				Ce bingo nécessite javascript, vous ne pourrez pas générer de grille tant que vous ne l'aurez pas activé !
			</div>
		</noscript>
		<div id="bingoGrid"></div>
		<div class="btn-group">
		<a class="btn" id="generateNewBingo" href="#"><i class="icon-th"></i> Générer une nouvelle grille</a>
		<a class="btn" id="uncheckAll" href="#"><i class="icon-table"></i> Tout décocher</a>
		<a class="btn" id="itemsListToggle" href="#"><i class="icon-legal"></i> Voir la liste complète des #points</a>
		</div>
		<br />
		<div id="itemsListWrapper"></div>
    </div>
    
    <div id="footer">
      <div class="container">
        <p class="muted credit centered">Site créé par Sylvain Boissel (<i class="icon-twitter"></i> <a href="http://twitter.com/sboissel">Twitter</a>) – <a href="http://ashtree.eu/wordpress/jqbingo/" alt="À propos">À propos</a> </p>
      </div>
    </div>

    
	<script>
		$(document).ready(function() {
			/* TODO allow to load data from an external file (php ? ajax ?) */
			var Title		= "Wikibingo";
			var Subtitles	= new Array(
				"[JJ] On ne le sait pas assez, mais nous avons quand même un métier à risques.",
				"[Ramina] N’empêche quand des apatosaurus  s’accouplaient, ça devait faire des microséismes",
				"[ironie] Malgré tout l'argent public gaspillé avec les astronomes, faut toujours qu'ils mettent les dates d'éclipses les jours de ciel nuageux. "
				);/*Merci IRC sur le coup :) https://meta.wikimedia.org/wiki/IRC/wikipedia-fr/extraits_choisis */
			var Items		= new Array(
				"Inclusionnistes",
				"Suppressionnistes",
				"Page à supprimer",
				"Fin de la civilisation occidentale",
				"Menace de procès",
				"Marronier",
				"Arbitrage",
				"Blocage",
				"Bannissement",
				"Requêtes aux admins",
				"Durée du mandat d’admin",
				"N'hésitez pas !",
				"Licence libre",
				"Ici on parle français !",
				"{{contre}}",
				"{{pour}}",
				"Conflit de modification",
				"Typographie",
				"Infobox",
				"IP",
				"Renommage",
				"Sources",
				"Attaque personnelle",
				"Wikipédia",
				"Commons",
				"Wikidata",
				"Wikimedia Foundation",
				"Wikisource",
				"Wiktionary",
				"Wikivoyage",
				"Wikinews",
				"Ne mordez pas les nouveaux",
				"Endive",
				"Chicon",
				"Actualités",
				"Nouveaux espaces de nom",
				"Article de qualité",
				"Bon article",
				"Apostrophe",
				"Administrateur",
				"Bureaucrate"
				);
		
			
			if (Title != "") {
				$('title').html(Title);
				$('.title').html(Title);
			}
			
			if (Subtitles.length != 0) {
				var Subtitle = Subtitles[Math.floor(Math.random()*Subtitles.length)];
				$('title').append(' – ' + Subtitle);
				$('.subtitle').html(' – ' + Subtitle);
			}
				
			function GenerateBingoGrid() {
				// Gets 25 *differents* elements from the list, then sorts it into a 5x5 table
				var BingoList = new Array();
				
				while ( BingoList.length < 25 ) {
					if ( BingoList.length == 12 ) { 
						BingoList.push("<span class='forced-okcell'><i class='icon-star'></i></span>");
					} else if ( BingoList.length < Items.length ) {
						var item = Items[Math.floor(Math.random()*Items.length)];
						if ($.inArray(item, BingoList) == -1) {
							BingoList.push(item);
						}
					} else {
						BingoList.push("<span class='forced-disabled'>Ajoutez des éléments à la liste de mots !</span>");
					}
				}		
			
				
				var BingoTable = '<table class="table table-bordered">';
				BingoTable += "<tr>";
				
				$.each(BingoList,function(i) {
					var $item = BingoList[i];
					if ($item.search('forced-okcell') != -1) {
						BingoTable += "<td class='forced okcell'>" + $item + "</td>";
					} else if ($item.search('forced-disabled') != -1) {
						BingoTable += "<td class='forced disabled'>" + $item + "</td>";
					} else {
						BingoTable += "<td>" + $item + "</td>";
					}
					if (((i+1)%5 == 0)&& (i <= 24)) {
						BingoTable += "</tr><tr>";
					}
				});
				
				BingoTable += "</tr>";
				BingoTable += "</table>";
				
				$("#bingoGrid").html(BingoTable);
			}
			
			GenerateBingoGrid();
			
			// Complete list of the items
			$("#itemsListWrapper").hide();
			var itemsList = "<p>" + Items.length + " éléments : </p>";
			itemsList += '<div id="itemsList"><ul>';
			$.each(Items.sort(),function(i) {
				itemsList += "<li>"+ Items[i] + "</li>";
			});
			itemsList += "</ul></div>";
			$("#itemsListWrapper").html(itemsList);
			
			// Buttons
			$("#generateNewBingo").click(function() {
				location.reload();
				//GenerateBingoGrid();
				return false;
			});
			
			$("#itemsListToggle").click(function() {
				$("#itemsListWrapper").toggle('fast');
				return false;
			});
			
			$("#uncheckAll").click(function() {
				$('td').not('.forced').removeClass();
			});
			
			// Color change on click
			$('td').click(function() {
				if (! $(this).hasClass('forced')) {
					$(this).toggleClass('okcell');
				}
				
				// If full row
				if($(this).parent().children(".okcell").length == 5) {
					$(this).parent().children().addClass('okline');
				} else {
					$(this).parent().children().removeClass('okline');
				}
				
				// If full column
				var column=$(this).index()+1;
				var counter=0;
				$('tr>td:nth-child('+column+')').each(function(index){
					if ($(this).hasClass("okcell")) { counter++; }
				});
				if(counter == 5) {
					$('tr>td:nth-child('+column+')').addClass('okcolumn');
				} else {
					$('tr>td:nth-child('+column+')').removeClass('okcolumn');
				}
				
				// If full diagonal from Up left to down right 
				if ($('tr:nth-child(1)>td:nth-child(1)').hasClass('okcell') &&
				    $('tr:nth-child(2)>td:nth-child(2)').hasClass('okcell') &&
				    $('tr:nth-child(3)>td:nth-child(3)').hasClass('okcell') &&
				    $('tr:nth-child(4)>td:nth-child(4)').hasClass('okcell') &&
				    $('tr:nth-child(5)>td:nth-child(5)').hasClass('okcell')) {
						$('tr:nth-child(1)>td:nth-child(1)').addClass('okdiag1');
						$('tr:nth-child(2)>td:nth-child(2)').addClass('okdiag1');
						$('tr:nth-child(3)>td:nth-child(3)').addClass('okdiag1');
						$('tr:nth-child(4)>td:nth-child(4)').addClass('okdiag1');
						$('tr:nth-child(5)>td:nth-child(5)').addClass('okdiag1');
				} else {
						$('tr:nth-child(1)>td:nth-child(1)').removeClass('okdiag1');
						$('tr:nth-child(2)>td:nth-child(2)').removeClass('okdiag1');
						$('tr:nth-child(3)>td:nth-child(3)').removeClass('okdiag1');
						$('tr:nth-child(4)>td:nth-child(4)').removeClass('okdiag1');
						$('tr:nth-child(5)>td:nth-child(5)').removeClass('okdiag1');
				}
				
				// If full diagonal form up right to down left
				if ($('tr:nth-child(1)>td:nth-child(5)').hasClass('okcell') &&
				    $('tr:nth-child(2)>td:nth-child(4)').hasClass('okcell') &&
				    $('tr:nth-child(3)>td:nth-child(3)').hasClass('okcell') &&
				    $('tr:nth-child(4)>td:nth-child(2)').hasClass('okcell') &&
				    $('tr:nth-child(5)>td:nth-child(1)').hasClass('okcell')) {
						$('tr:nth-child(1)>td:nth-child(5)').addClass('okdiag2');
						$('tr:nth-child(2)>td:nth-child(4)').addClass('okdiag2');
						$('tr:nth-child(3)>td:nth-child(3)').addClass('okdiag2');
						$('tr:nth-child(4)>td:nth-child(2)').addClass('okdiag2');
						$('tr:nth-child(5)>td:nth-child(1)').addClass('okdiag2');
				} else {
						$('tr:nth-child(1)>td:nth-child(5)').removeClass('okdiag2');
						$('tr:nth-child(2)>td:nth-child(4)').removeClass('okdiag2');
						$('tr:nth-child(3)>td:nth-child(3)').removeClass('okdiag2');
						$('tr:nth-child(4)>td:nth-child(2)').removeClass('okdiag2');
						$('tr:nth-child(5)>td:nth-child(1)').removeClass('okdiag2');
				}
				
				// If full grid
				if ($('.okcell').length == 25) {
					$('td').addClass('fullgrid');
				} else {
					$('td').removeClass('fullgrid');
				}
					
				return false;
			});
			
		});
	</script>
	<!-- Piwik --> 
	<script type="text/javascript">
		var pkBaseURL = (("https:" == document.location.protocol) ? "https://ashtree.eu/piwik/" : "http://ashtree.eu/piwik/");
		document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
	</script><script type="text/javascript">
		try {
		var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 1);
		piwikTracker.trackPageView();
		piwikTracker.enableLinkTracking();
		} catch( err ) {}
	</script><noscript><p><img src="http://ashtree.eu/piwik/piwik.php?idsite=1" style="border:0" alt="" /></p></noscript>
	<!-- End Piwik Tracking Code -->
  </body>
</html>
