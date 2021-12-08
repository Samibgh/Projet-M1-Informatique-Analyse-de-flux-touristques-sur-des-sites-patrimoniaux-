<!-- Itsukushima.php -->
<?php
	include("header.html");
	require("classes.php");
	try {
		require("connection.php"); //connexion à la base de données 
		/// Par Année
		//Requete
		$rqArrow = new Requete($c, "SELECT Year, Region, Lattitude, Longitude, Site, Lattitude_Site, Longitude_Site, count(*) as nbPersonne FROM scrapeddatabase Where Region not like 'unknown' and Site like 'Itsukushima' Group By Region, Year, Site Order by count(*) desc");
		$rqCircle = new Requete($c, "SELECT Year, Region, Lattitude, Longitude FROM scrapeddatabase Where Region not like 'unknown' and Site like 'Itsukushima' Group By Region, Year");
		$rqCircleBase = new Requete($c, "SELECT Year, Site, Lattitude_Site, Longitude_Site FROM scrapeddatabase Where Region not like 'unknown' and Site like 'Itsukushima' Group By Region, Year");
		
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
		$rqArrow = new Requete($c, "SELECT Region, Lattitude, Longitude, Site, Lattitude_Site, Longitude_Site, count(*) as nbPersonne FROM scrapeddatabase Where Region not like 'unknown' and Site like 'Itsukushima' Group By Region, Site Order by count(*) desc");
		$rqCircle = new Requete($c, "SELECT Region, Lattitude, Longitude FROM scrapeddatabase Where Region not like 'unknown' and Site like 'Itsukushima' Group By Region");
		$rqCircleBase = new Requete($c, "SELECT Site, Lattitude_Site, Longitude_Site FROM scrapeddatabase Where Region not like 'unknown' and Site like 'Itsukushima' Group By Region");
		
		//Appel
		$rqArrow->executer();
		$rqCircle->executer();
		$rqCircleBase->executer();
		
		//Mise en Tableau
		$TableArrowToutes = new Tableau($rqArrow->data(), "");
		$TableCircleToutes = new Tableau($rqCircle->data(), "");
		$TableCircleBaseToutes = new Tableau($rqCircleBase->data(), "");
		
		//Données pour le graphique en barre
		$ContPersonne = new Requete($c,"SELECT Region, count(*) as nbPersonne FROM scrapeddatabase Where Region not like 'unknown' and Site like 'Itsukushima' Group By Region Order By count(*) desc");
		$ContPersonne->executer();
		$TableContPersonne = new Tableau($ContPersonne->data(), "");
		
		//Données pour le graphique en barre
		$PaysCont = new Requete($c,"SELECT Localisation, count(*) as nbPersonne FROM scrapeddatabase Where Localisation not like 'unknown' and Site like 'Itsukushima' Group By Localisation Order By count(*) desc Limit 10");
		$PaysCont->executer();
		$TablePaysCont = new Tableau($PaysCont->data(), "");
		
		//Données pour le graphique en barre
		$YearCont = new Requete($c,"SELECT Year, count(*) as nbPersonne FROM scrapeddatabase Where Localisation not like 'unknown' and Site like 'Itsukushima' Group By Year Order By Year desc ");
		$YearCont->executer();
		$TableYearCont = new Tableau($YearCont->data(), "");
		
		//Données pour la liste déroulante 
		$Year = new Requete($c,"SELECT Year FROM scrapeddatabase where Site like 'Itsukushima' Group By Year Order By Year asc ");
		$Year->executer();
		$TableYear = new Tableau($Year->data(), "");
		
	} catch(PDOException $erreur) {
		echo "<p>Erreur : " . $erreur->getMessage() . "</p>\n";
	}
	
?>

	
		<div class="title">
			<h2>Le Sanctuaire d'Itsukushima :</h2>
		</div>
		<div class="presentation">
			<p>Le Sanctuaire d'Itsukushima est situé dans la ville de Hatsukaichi sur l'île de Miyajima, dans la préfecture de Hiroshima au Japon.
			Il est inscrit sur la liste du patrimoine mondial de l'Unesco depuis 1996. Il est également réputé pour être l'un des plus beaux 
				lieux du Japon. </p>
			<p>Son appelation vient du nom de l'île qui s'appelait autrefois Itsukushima. Le nom s'est donc transformé en Miyajima qui 
				veut littéralement dire "l'île du sanctuaire". Le grand torii, trempant les pieds dans l'eau, marque l'entrée du lieu le plus
				sacré de l'île de Miyajima.</p>
		</div>
		
		<img src="https://www.japan-experience.com/sites/default/files/styles/scale_740/public/legacy/japan_experience/1581517130879.jpg?itok=k9uOoxBA " alt="Sanctuaire d'Itsukushima" style=" height : 20%;width : 30%; display:inline-block; margin-left : 230px;"/>
		
		<img src="https://www.japan-experience.com/sites/default/files/styles/scale_740/public/legacy/japan_experience/1581516954440.jpg?itok=AVAUFoG- " alt="Torii du sanctuaire" style="width : 40%; display:inline-block;"/>
		
		<div class="analyseFlux">
			<br>
			<p>Lors de notre projet, nous avons décidé de représenté des flux de visteurs qui venaient voir ce patrimoine afin d'en tirer des informations intéressantes. C'est pour cela que  nous avons mis les données récuperés sur TripAdvisor sous forme de carte et également 
				de graphique.</p>
			<p>La carte ci-dessous les flux ainsi que le nombre de visiteurs par continent et par année. Nous avons le choix d'observer les flux pour toutes les années confondus ou bien 
			de choisir une date parmi la liste déroulantes. Lorsque que nous passons le curseur de la souris sur une flèche, cela affiche le nombre de visteur qui sont venus 
			au Japon voir le patrimoine depuis un certains continent. Plus la flèche est grosse, plus le nombre de visteur est élevé.</p>
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
	echo '<br><p>Nous observons sur la carte que l\'année la plus active à été celle de 2016. En effet, beaucoup de visteurs sont venus cette année notamment d\'Europe. L\'année la moins active est celle de 2020.<p><br>';

	
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
	</div>';
	echo '<br><p>Nous observons sur le graphique que c\'est en 2016 que le sanctuaire a accuilli le plus de visiteur. Peu de visiteur sont venus en 2020 surêment dû à la pandémie de COVID19. Plus 
	de la moitiée des visiteurs sont venus entre l\'année 2014 et 2017.</p>';
	
	
	echo '<br><p>Les 2 diagrames de Pareto ci-dessous représentes le nombre de visteur regroupés par continent et l\'analyse des pays où l\'on recence le plus de visteur issus de ceux-ci.</p><br>';
	
	echo '<table>
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
	Le graphique de droite nous montre que la plus part des visiteurs sont issus d\'Australie.<br></p>
	';
	echo '<br><p>La carte suivante montre le nombre de visiteurs à Itsukushima qui proviennent des différents états des Etats-Unis.</p><br>';
	echo '<iframe id="carteUSA" src="itsukushima_USA.html"></iframe>';
	echo '<br><br><p>On voit qu\'il y a plus de Californien qui viennent visister le sanctuaire que des habitants d\'autre états.<br></p>';
	echo '</div>';
	
?>	

</div>

<?php
include("foooter.html");
?>