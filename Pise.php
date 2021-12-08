<!-- Pise.php -->
<?php
	include("header.html");
	require("classes.php");
	try {
		require("connection.php"); //connexion à la base de données 
		/// Par Année
		//Requete
		$rqArrow = new Requete($c, "SELECT Year, Region, Lattitude, Longitude, Site, Lattitude_Site, Longitude_Site, count(*) as nbPersonne FROM scrapeddatabase Where Region not like 'unknown' and Site like 'Pise' Group By Region, Year, Site Order by count(*) desc");
		$rqCircle = new Requete($c, "SELECT Year, Region, Lattitude, Longitude FROM scrapeddatabase Where Region not like 'unknown' and Site like 'Pise' Group By Region, Year");
		$rqCircleBase = new Requete($c, "SELECT Year, Site, Lattitude_Site, Longitude_Site FROM scrapeddatabase Where Region not like 'unknown' and Site like 'Pise' Group By Region, Year");
		
		//Appel
		$rqArrow->executer();
		$rqCircle->executer();
		$rqCircleBase->executer();
		
		//Mise en Tableau
		$TableArrow = new Tableau($rqArrow->data(), "");
		$TableCircle = new Tableau($rqCircle->data(), "");
		$TableCircleBase = new Tableau($rqCircleBase->data(), "");
		
		/// Au global
		//Requete
		$rqArrow = new Requete($c, "SELECT Region, Lattitude, Longitude, Site, Lattitude_Site, Longitude_Site, count(*) as nbPersonne FROM scrapeddatabase Where Region not like 'unknown' and Site like 'Pise' Group By Region, Site Order by count(*) desc");
		$rqCircle = new Requete($c, "SELECT Region, Lattitude, Longitude FROM scrapeddatabase Where Region not like 'unknown' and Site like 'Pise' Group By Region");
		$rqCircleBase = new Requete($c, "SELECT Site, Lattitude_Site, Longitude_Site FROM scrapeddatabase Where Region not like 'unknown' and Site like 'Pise' Group By Region");
		
		//Appel
		$rqArrow->executer();
		$rqCircle->executer();
		$rqCircleBase->executer();
		
		//Mise en Tableau
		$TableArrowToutes = new Tableau($rqArrow->data(), "");
		$TableCircleToutes = new Tableau($rqCircle->data(), "");
		$TableCircleBaseToutes = new Tableau($rqCircleBase->data(), "");
		
		//Données pour le graphique en barre
		$ContPersonne = new Requete($c,"SELECT Region, count(*) as nbPersonne FROM scrapeddatabase Where Region not like 'unknown' and Site like 'Pise' Group By Region Order By count(*) desc");
		$ContPersonne->executer();
		$TableContPersonne = new Tableau($ContPersonne->data(), "");
		
		//Données pour le graphique en barre
		$PaysCont = new Requete($c,"SELECT Localisation, count(*) as nbPersonne FROM scrapeddatabase Where Localisation not like 'unknown' and Site like 'Pise' Group By Localisation Order By count(*) desc Limit 10");
		$PaysCont->executer();
		$TablePaysCont = new Tableau($PaysCont->data(), "");
		
		//Données pour le graphique en barre
		$YearCont = new Requete($c,"SELECT Year, count(*) as nbPersonne FROM scrapeddatabase Where Localisation not like 'unknown' and Site like 'Pise' Group By Year Order By Year desc ");
		$YearCont->executer();
		$TableYearCont = new Tableau($YearCont->data(), "");
		
		//Données pour la liste déroulante 
		$Year = new Requete($c,"SELECT Year FROM scrapeddatabase where Site like 'Pise' Group By Year Order By Year asc ");
		$Year->executer();
		$TableYear = new Tableau($Year->data(), "");
		
	} catch(PDOException $erreur) {
		echo "<p>Erreur : " . $erreur->getMessage() . "</p>\n";
	}
	
?>

	
		<div class="title">
			<h2>La Tour de Pise :</h2>
		</div>
		<div class="presentation">
			<p>La Tour de Pise est située en Toscane dans la ville de Pise, en Italie. Elle fait partie des monuments de la plazza del Miracoli qui est inscrit au patrimoine de mondial de 
			l'UNESCO. Mondialement connue, elle est un des symboles d'Italie et l'emblême de Pise. </p>
			<p>Outre le fait qu'elle soit considérée comme un chef d'oeuvre de l'art roman toscan en marbre, sa célébrité vient notamment de son inclinaison caractéristique qui est apparue 
			rapidement lors de sa construction. Ce défaut serait dû à un affessement de terrain à cause d'une roche nommée "La marne".</p>
		</div>
		
		<img src="https://media.lesechos.com/api/v1/images/view/5bf640123e45464d877eae40/1280x720/060191815900-web-tete.jpg" alt="La tour de pise" style=" height : 25%;width : 35%; display: block;
    margin-left: auto;
    margin-right: auto"/>
				
		<div class="analyseFlux">
			<br>
			<p>Lors de notre projet, nous avons décidé de représenté des flux de visteurs qui venaient voir ce patrimoine afin d'en tirer des informations intéressantes. C'est pour cela que  nous avons mis les données récuperés sur TripAdvisor sous forme de carte et également 
				de graphique.</p>
			<p>La carte ci-dessous affiche les flux ainsi que le nombre de visiteurs par continent et par année. Nous avons le choix d'observer les flux pour toutes les années confondus ou bien 
			de choisir une date parmi la liste déroulantes. Lorsque que nous passons le curseur de la souris sur une flèche, cela affiche le nombre de visteur qui sont venus 
			en Italie voir le patrimoine depuis un certains continent. Plus la flèche est grosse, plus le nombre de visteur est élevé.</p>
<?php		
	
	echo '<select id="selectDate" onchange="updateDate()">';
	echo '	<option value="Toutes">Toutes</option>';
	$Years = array();
	foreach($TableYear->data() as $ligne){
		array_push($Years, $ligne["Year"]);
	}
	$Years = array_unique($Years);
	foreach($Years as $Year){
		echo '<option value="'.$Year.'">'.$Year.'</option>';
	}
	echo '</select>';
	
	
	echo '<div>';
	$ArrowTables["ArrowTableToutes"] = $TableArrowToutes->data();
	$CircleTables["CircleTableToutes"] = $TableCircleToutes->data();
	$CircleBaseTables["CircleBaseTableToutes"] = $TableCircleBaseToutes->data();
	foreach($Years as $Year){
		
		$ArrowTables += ["ArrowTable".$Year => array()];
		$CircleTables += ["CircleTable".$Year => array()];
		$CircleBaseTables += ["CircleBaseTable".$Year => array()];
	}
	foreach($TableArrow->data() as $ligne){
		foreach($Years as $Year){
			if($ligne["Year"] == $Year){
				array_push($ArrowTables["ArrowTable".$Year], $ligne);
			}
		}
	}
	foreach($TableCircle->data() as $ligne){
		foreach($Years as $Year){
			if($ligne["Year"] == $Year){
				array_push($CircleTables["CircleTable".$Year], $ligne);
			}
		}
	}
	foreach($TableCircleBase->data() as $ligne){
		foreach($Years as $Year){
			if($ligne["Year"] == $Year){
				array_push($CircleBaseTables["CircleBaseTable".$Year], $ligne);
			}
		}
	}
	echo '<script type="text/javascript">
		function updateDate() {
			var selectedDate = document.getElementById("selectDate").value;
			var divToShow = document.getElementById("chartDiv"+selectedDate);
			var divToHide = document.querySelectorAll(\'[class="chartDiv"]\');
			for (var i = 0; i < divToHide.length; i++) {
				divToHide[i].style.display = "none";
			}
			divToShow.style.display = "block";
		}
		function updateNote() {
			var selectedNote = document.getElementById("selectNote").value;
			var divToShow = document.getElementById(selectedNote);
			var divToHide = document.querySelectorAll(\'[class="chartNote"]\');
			for (var i = 0; i < divToHide.length; i++) {
				divToHide[i].style.display = "none";
			}
			divToShow.style.display = "block";
		}';
	$YearsSelection = array("Toutes");
	$YearsSelection = array_merge($YearsSelection, $Years);
	foreach($YearsSelection as $Year){
		echo '
//---------------------------------'.$Year.'---------------------------------------
			// Create map instance
			var chart = am4core.create("chartDiv'.$Year.'", am4maps.MapChart);

			// Set map definition
			chart.geodata = am4geodata_worldLow;

			// Set projection
			chart.projection = new am4maps.projections.Miller();0

			// Create map polygon series
			var polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());

			// Make map load polygon (like country names) data from GeoJSON
			polygonSeries.useGeodata = true;

			// Configure series
			var polygonTemplate = polygonSeries.mapPolygons.template;
			polygonTemplate.fill = am4core.color("#1F95EF");

			// Remove Antarctica
			polygonSeries.exclude = ["AQ"];

			// Create image series
			var imageSeries = chart.series.push(new am4maps.MapImageSeries());

			// Create a circle image in image series template so it gets replicated to all new images
			var imageSeriesTemplate = imageSeries.mapImages.template;
			var circle = imageSeriesTemplate.createChild(am4core.Circle);
			circle.radius = 5;
			circle.fill = am4core.color("#181E97");
			circle.strokeWidth = 3;
			circle.nonScaling = true;
			circle.tooltipText = "{title}";

			// Set property fields
			imageSeriesTemplate.propertyFields.latitude = "latitude";
			imageSeriesTemplate.propertyFields.longitude = "longitude";

			// Add data for the site and regions
			imageSeries.data = [';
		$ligne = $CircleBaseTables["CircleBaseTable".$Year][0];
		$Region = $ligne["Site"];
		$Latitude = $ligne["Lattitude_Site"];
		$Longitude = $ligne["Longitude_Site"];
		echo '
			{"latitude": '.$Latitude.', "longitude": '.$Longitude.', "title": "'.$Region.'"},';
		foreach ($CircleTables["CircleTable".$Year] as $ligne) {
			$Region = $ligne["Region"];
			$Latitude = $ligne["Lattitude"];
			$Longitude = $ligne["Longitude"];
			echo '
			{"latitude": '.$Latitude.', "longitude": '.$Longitude.', "title": "'.$Region.'"},';
		}
		echo '];

			// Add line series';
		$i = 0;
		foreach ($ArrowTables["ArrowTable".$Year] as $ligne) {
			$i += 1;
			$Visiteurs = $ligne["nbPersonne"];
			$Largeur = log($Visiteurs + 1);
			$Latitude = $ligne["Lattitude"];
			$Longitude = $ligne["Longitude"];
			$LatSite = $ligne["Lattitude_Site"];
			$LongSite = $ligne["Longitude_Site"];

			echo '
			var lineSeries'.$i.' = chart.series.push(new am4maps.MapLineSeries());
			lineSeries'.$i.'.mapLines.template.strokeWidth = '.$Largeur.';
			lineSeries'.$i.'.mapLines.template.stroke = am4core.color("#181E97");
			lineSeries'.$i.'.mapLines.template.nonScalingStroke = true;
			lineSeries'.$i.'.mapLines.template.tooltipText = "Nombre de visiteurs : '.$Visiteurs.'";
			lineSeries'.$i.'.mapLines.template.fill = am4core.color("#181E97");

			var line'.$i.' = lineSeries'.$i.'.mapLines.create();
			line'.$i.'.multiGeoLine = [[
			{ "latitude": '.$Latitude.', "longitude": '.$Longitude.' },
			{ "latitude": '.$LatSite.', "longitude": '.$LongSite.' }
			]];
			';
		}
	}
	echo '</script>'; 
		
	
	echo '
		<div id="chartDivToutes" style="width: 1100px; height: 600px; margin-left : 120px; border : solid;" class="chartDiv" ></div>';
	foreach($Years as $Year){
		echo '
		<div id="chartDiv'.$Year.'" class="chartDiv" style="display: none;width: 1100px; height: 600px; margin-left : 120px; border : solid;"></div>';
	}
	
	echo '</div>';
	echo '<br> <p>Nous observons sur la carte que l\'année la plus active à été celle de 2019. En effet, beaucoup de visteurs sont venus cette année notamment d\'Amérique du Nord. L\'année la moins active est celle de 2021.<p><br>';
	
	
	echo '<p>Le graphique ci-dessous représente nombre de visiteurs tout continent confondus et par année. Lorsque nous passons le curseur de la souris sur les différentes barre du diagramme, 
		nous avons le nombre exact de visiteur selon l\'année.</p>';
	
	echo '<div class=col3>';
	$TableYearCont = $TableYearCont->data();
	echo '
	<canvas id="graphique3" width="100px" height="100px"></canvas>
	<script>
		// l\'identifiant est celui du canevas
		var ctx = document.getElementById("graphique3").getContext("2d");
		// création du graphique
		var myChart = new Chart(ctx, {
		type: "bar",   // le type du graphique
		data: {        // les données
		labels: [';
		
	foreach ($TableYearCont as $ligne) {
		$Year = $ligne["Year"];
		if($ligne["Year"] == $TableYearCont[0]["Year"]){
			echo "'$Year'";
		} else {
			echo ",'$Year'";
		}
	}
	echo '],
	datasets: [{
			label: "Nombre de visiteurs par annnées",
			data: [';
	foreach ($TableYearCont as $ligne) {
		$NbPersonne = $ligne["nbPersonne"];
		if($ligne["Year"] == $TableYearCont[0]["Year"]){
			echo "$NbPersonne";
		} else {
			echo ",$NbPersonne";
		}
	}
	echo '],
		backgroundColor: "#46C481",
			   }]
			}
		});
	</script>
	</div>
	
	<br><p>Nous observons sur le graphique que c\'est en 2019 que le site a accuilli le plus de visiteur. Peu de visiteur sont venus en 2018 ainsi qu\'en 2020 et 2021 surêment dû à la pandémie de COVID19. 
	Nous avons une forte différence entre l\'année 2019 et les autres années.</p>
	
	
	<br><p>Les 2 diagrames de Pareto ci-dessous représentes le nombre de visteur regroupés par continent et l\'analyse des pays où l\'on recence le plus de visteur issus de ceux-ci.</p><br>
	
	<table>
	<div class=col>';
	
	$TableContPersonne = $TableContPersonne->data();
	echo '
	<canvas id="graphique" width="100px" height="100px"></canvas>
	<script>
		// l\'identifiant est celui du canevas
		var ctx = document.getElementById("graphique").getContext("2d");
		// création du graphique
		var myChart = new Chart(ctx, {
		type: "bar",   // le type du graphique
		data: {        // les données
		labels: [';
		
	foreach ($TableContPersonne as $ligne) {
		$Region = $ligne["Region"];
		if($ligne["Region"] == $TableContPersonne[0]["Region"]){
			echo "'$Region'";
		} else {
			echo ",'$Region'";
		}
	}
	echo '],
	datasets: [{
			label: "Nombre de visteurs par continent",
			data: [';
	foreach ($TableContPersonne as $ligne) {
		$NbPersonne = $ligne["nbPersonne"];
		if($ligne["Region"] == $TableContPersonne[0]["Region"]){
			echo "$NbPersonne";
		} else {
			echo ",$NbPersonne";
		}
	}
	echo '],
			backgroundColor: "#F64C3E",
			   }]
			}
		});
	</script>
	</div>';
	
	echo '<div class=col>';
	$TablePaysCont = $TablePaysCont->data();
	echo '
	<canvas id="graphique2" width="100px" height="100px"></canvas>
	<script>
		// l\'identifiant est celui du canevas
		var ctx = document.getElementById("graphique2").getContext("2d");
		// création du graphique
		var myChart = new Chart(ctx, {
		type: "bar",   // le type du graphique
		data: {        // les données
		labels: [';
		
	foreach ($TablePaysCont as $ligne) {
		$Localisation = $ligne["Localisation"];
		if($ligne["Localisation"] == $TablePaysCont[0]["Localisation"]){
			echo "'$Localisation'";
		} else {
			echo ",'$Localisation'";
		}
	}
	echo '],
	datasets: [{
			label: "Nombre de visiteurs par pays du monde",
			data: [';
	foreach ($TablePaysCont as $ligne) {
		$NbPersonne = $ligne["nbPersonne"];
		if($ligne["Localisation"] == $TablePaysCont[0]["Localisation"]){
			echo "$NbPersonne";
		} else {
			echo ",$NbPersonne";
		}
	}
	echo '],
		backgroundColor: "#F64C3E",
			   }]
			}
		});
	</script>
	</table>';	
	
	echo '<br><br><p>On observe sur le graphique de gauche que le continent où est issus le plus de visiteur est l\'Eurupe. Peu de personne viennent d\'Afrique ou d\'Amérique Centrale.
	Le graphique de droite nous montre plus de la moitiée des visiteurs sont issus du Royaume-Unis.<br></p>';
	
	echo '<br><p>La carte suivante montre le nombre de visiteurs à Pise qui proviennent des différents états des Etats-Unis.</p><br>';
	echo '<iframe id="carteUSA" src="pise_USA.html"></iframe>';
	echo '<br><br><p>On voit que la Californie et la floride sont les états des USA d\'où proviennent le plus les visiteurs à Pise.<br></p>';
	
	echo '<div id="restoPise" style="margin-bottom:30px">';
	echo '<br><p>Nous avons choisi de réaliser une analyse sur les restaurant autours de la tour de Pise, pour cela nous avons utilisé les avis TripAdvisor des restaurants à moins d\'1Km du site et avons regardé les notes de ceux ci 
	afin de réaliser les différents graphiques suivants : </p>';
	
	echo '<div class=col>';
	echo '<p>Ratio de notes excellentes sur l\'ensemble des notes :</p>';
	echo '<iframe width="75%" height="350" src="ratio_excellent_pise.html"></iframe>';
	echo '</div>';
	
	echo '<div class=col>';
	echo '<p>Les 2 restaurants les plus recommandés selon les différentes notes :</p>';
	echo '<iframe width="75%" height="350" src="recommandation.html"></iframe>';
	echo '</div>';
	
	echo '<br><select id="selectNote" onchange="updateNote()" style="width:200px; margin-bottom:15px">';
	echo '	<option value="food">Nourriture</option>';
	echo '	<option value="service">Service</option>';
	echo '	<option value="atmosphere">Atmosphere</option>';
	echo '	<option value="rapport">Qualité/Prix</option>';	
	echo '</select>';
	
	echo '<div id="food" class="chartNote">';
	echo '<p>Note de la qualité de la nourriture des restaurants :</p>';
	echo '<iframe width="600" height="350" src="food.html"></iframe>';
	echo '</div>';
	
	echo '<div id="rapport" class="chartNote" style="display: none;">';
	echo '<p>Note du rapport qualité prix :</p>';
	echo '<iframe width="600" height="350" src="value.html"></iframe>';
	echo '</div>';
	
	echo '<div id="service" class="chartNote" style="display: none;">';
	echo '<p>Note du service :</p>';
	echo '<iframe width="600" height="350" src="service.html"></iframe>';
	echo '</div>';
	
	echo '<div id="atmosphere" class="chartNote" style="display: none;">';
	echo '<p>Note de l\'atmosphere :</p>';
	echo '<iframe width="600" height="350" src="atmosphere.html"></iframe>';
	echo '</div>';
	
	echo '<p>Voilà qui pourra vous aider à choisir des restaurants si vous avez prévu de visiter Pise.</p>';	
	echo '</div>';
	
	echo '</div>';
	
?>	

</div>

<?php
include("foooter.html");
?>