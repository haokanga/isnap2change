
<html class="no-js" lang="en-au" >
<head id="Head">
<title>Standard Drink Tool</title>
    <link href="./DesktopModules/ATAStandardDrinkTool/css/standard-drinks-tool.css" type="text/css" rel="stylesheet"/>
	<script src="js/jquery-1.12.3.js"></script>
	<script src="./DesktopModules/ATAStandardDrinkTool/js/jquery-ui.js" type="text/javascript"></script>
<body id="Body">
<script>
    gtmDataLayer = [];
</script>


    <form method="post" action="/Alcohol-Your-Health/What-is-a-Standard-Drink/Standard-Drink-Tool" id="Form" enctype="multipart/form-data">
  
<div class="container"><!-- whole site -->
		


<div class="row"><!-- body content -->
    <div id="content" class="row-content clearfix">
		<div class="col-left"><!-- left column -->
			<div class="col-inner">
           	 	<h1>Standard Drink Tool</h1>
            	<div id="dnn_contentPane" class="contentPane"><div class="DnnModule DnnModule-DNN_HTML DnnModule-624"><a name="624"></a>
					<div class="DNNContainer_noTitle">
								<div class="standard-drinks-tool-intro"> 
									<div class="sdt-wrap clearfix">
										<div class="std-drink-tool-banner"> 
											<h3 class="std-drink-tool-heading">Do you know what a standard drink looks like?</h3> 
												<a class="btn begin-sd-tool">Pour a standard drink</a>
										</div>
									</div>	
									</div><!-- End_Module_624 -->
								</div>
							<div class="clear"></div>
</div>
</div><div class="DnnModule DnnModule-ATAStandardDrinkTool DnnModule-626"><a name="626"></a>
<div class="DNNContainer_noTitle">
	<div id="dnn_ctr626_ContentPane"><!-- Start_Module_626 --><div id="dnn_ctr626_ModuleContent" class="DNNModuleContent ModATAStandardDrinkToolC">
	<!--CDF(Css|/DesktopModules/ATAStandardDrinkTool/css/standard-drinks-tool.css?cdv=48)-->

<!-- DRINK TOOL WRAPPER -->
	<div id="standard-drink-tool-wrapper">
		<!-- STEP 1 -->
		<div class="drink-containers-wrapper">
			<div class="flexslider carousel">
				<ul class="drink-containers slides">

				</ul>

				<ul class="drink-container-template">
					<li>
						<div class="container-thumb">
							<div class="container-tick "><span class="sr-only">selected</span></div>
							<img class="container-image" alt="Drink Image" />
							<div class="container-title">Title</div>
							<div class="container-capacity">300ml</div>
							<input type="radio" name="drinkContainer[]" class="radioDrinkContainer" />
						</div>
					</li>
				</ul>
			</div>

			<div class="drink-container-buttons">
				<a class="btn drink-container-selected">Next</a>
			</div>
		</div>

		<!-- STEP 2 -->
		<div class="drink-types-wrapper clearfix">
			<div class="selected-container">
				<img class="container-image" alt="Drink Image" src="images/standard-drinks/thumb-small-wine-glass.jpg">
				<div class="container-title">Small Wine Glass</div>
				<div class="container-capacity">300ml</div>
			</div>

			<ul class="drink-types">

			</ul>

			<ul class="drink-types-template">
				<li>
					<div class="container-tick "><span class="sr-only">selected</span></div>
					<span class="drink-title">Title</span><br />
					<span class="drink-volume">10%</span>
					<input type="radio" name="drinkTypeContainer[]" class="drinkTypeContainer" />
				</li>
			</ul>

			<div class="drink-type-buttons">
				<a class="btn step1 js-back">Back</a>
				<a class="btn drinkTypeSelelected">Next</a>
			</div>
		</div>

		<!-- STEP 3 & 4 -->
		<div class="drink-wrapper">
			<div class="drink-inner-wrapper">
				<div class="drink-overflow">
					<div class="drink-mask" id="drink-mask"><img src="images/standard-drinks/img-glass-pint-mask.png" class="maskimage"></div>
					<div class="underlay-wrapper"><div class="drink-underlay"></div></div>

					<div class="underlay-wrapper topindex">
						<div id="drag-wrapper">
							<div class="arrow-left"></div>
							<div class="dragbox">
								<div class="filled"><strong><span>0</span>ml</strong></div>
								Your Pour

								<div class="dragbox-icon"></div>

								<div class="filledStandardDrink">
									<div class="approx">APPROX.</div>
									<div class="drink-measure"><span>0</span></div>
									Standard drinks
								</div>

							</div>
						</div>

						<div class="standardGuide">
							<div class="standardDrinkMl">
								<div class="approxml">APPROX.</div>
								<span>0</span>ml
								<div class="filledStandardDrink">
									<div class="approx">&nbsp;</div>
									<div class="drink-measure sd-num">1.0</div>
									Standard drinks
								</div>
							</div>
							<div class="arrow-right"></div>
						</div>
					</div>
				</div>
			</div>

			<div class="drink-choices">
				<div class="btn guessBtn">Check Standard Drink</div>
				<strong><span class="glassName"></span></strong> <span class="glasscapacity">0ml</span><br/>
				<strong><span class="drinkName"></span></strong> <span class="alcoholVolume"></span>% Alc. Vol
			</div>

			<div class="sdt-buttons">
				<a class="btn step2 back js-back">Back</a>
				<a class="btn step1 initTool js-restart">Start Again</a>
			</div>
		</div>
	</div>

<script src="./DesktopModules/ATAStandardDrinkTool/js/vendor/jquery.ui.touch-punch.min.js"></script>

<script src="./DesktopModules/ATAStandardDrinkTool/js/standard-drinks-tool.js"></script>

<script src="./DesktopModules/ATAStandardDrinkTool/js/vendor/jquery.flexslider.js"></script>

</div><!-- End_Module_626 --></div>
	<div class="clear"></div>
</div>
</div></div>
            </div>
		</div><!-- END left column -->

	</div>
</div>
<!-- END header content -->


	</div>
	<!-- END Off Canvas Menu container -->
</div>
<!-- END whole site -->
	</form>


</body>
</html>