<?php



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0, width=device-width, user-scalable=no">
    <title>Meal Planner</title>
    <link rel="stylesheet" href="./css/common.css">
    <link href='https://fonts.googleapis.com/css?family=Maitree|Lato:400,900' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="./js/vendor/jquery.js"></script>
    <style>
        .meal-planner-container {
            max-width: 1000px;
            margin: 20px auto;
            text-align: center;
        }
        .meal-planner-intro {
            max-width: 500px;
            margin: 10px auto;
        }
        .meal-planner-content {
            margin-top: 20px;
        }
        .meal-name {
            color: #fcee2d;
            margin: 20px 0;
        }
        .meal-content {
            padding: 10px 20px;
            border-radius: 10px;
            background-color: rgb(160, 160, 160);
            min-height: 100px;
            overflow: hidden;
        }
        .meal-content .fruit-item {
            float: left;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .fruit-title {
            margin: 20px 0;
        }
        .fruit-panel {
            display: none;
        }
        .fruit-panel-active {
            display: block;
        }

        .fruit-item-container,
        .fruit-item {
            width: 100px;
            height: 100px;
            margin: 0 auto 10px;
            background-color: rgb(61, 61, 61);
            border-radius: 5px;
        }
        .fruit-item {
            cursor: move;
        }
        .frtui-item-container .fruit-item {
            margin-bottom: 0;
        }
        .fruit-content .mini-row {
            margin: 0 -5px;
        }
        .fruit-content .col-3 {
            padding: 0 5px;
        }
        .fruit-item-icon {
            display: block;
            width: 60px;
            height: 60px;
            margin: 0 auto 0;
            background-size: 100% 100%;
        }
        .fruit-item-apples .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_apples.png")
        }
        .fruit-item-banana .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_banana.png")
        }
        .fruit-item-beef_stirfry .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_beef_stirfry.png")
        }
        .fruit-item-boiled_potato .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_boiled_potato.png")
        }
        .fruit-item-broccoli .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_broccoli.png")
        }
        .fruit-item-caesar_salad .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_caesar_salad.png")
        }
        .fruit-item-carrot .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_carrot.png")
        }
        .fruit-item-cheddar_cheese .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_cheddar_cheese.png")
        }
        .fruit-item-chicken_doner_kebab .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_chicken_doner_kebab.png")
        }
        .fruit-item-chicken_drumstick .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_chicken_drumstick.png")
        }
        .fruit-item-chicken_wings .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_chicken_wings.png")
        }
        .fruit-item-coffee .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_coffee.png")
        }
        .fruit-item-corn_flakes .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_corn_flakes.png")
        }
        .fruit-item-cream .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_cream.png")
        }
        .fruit-item-crumbed_fish .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_crumbed_fish.png")
        }
        .fruit-item-egg .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_egg.png")
        }
        .fruit-item-fast_food_burger .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_fast_food_burger.png")
        }
        .fruit-item-fried_rice .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_fried_rice.png")
        }
        .fruit-item-fries .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_fries.png")
        }
        .fruit-item-iced_tea .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_iced_tea.png")
        }
        .fruit-item-instant_noodles .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_instant_noodles.png")
        }
        .fruit-item-juice .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_juice.png")
        }
        .fruit-item-lamb_roast .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_lamb_roast.png")
        }
        .fruit-item-leafy_salad .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_leafy_salad.png")
        }
        .fruit-item-light_sour_cream .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_light_sour_cream.png")
        }
        .fruit-item-low_fat_yogurt .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_low_fat_yogurt.png")
        }
        .fruit-item-meat_pie .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_meat_pie.png")
        }
        .fruit-item-milk_glass .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_milk_glass.png")
        }
        .fruit-item-milo .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_milo.png")
        }
        .fruit-item-muesli .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_muesli.png")
        }
        .fruit-item-multigrain_bread .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_multigrain_bread.png")
        }
        .fruit-item-nesquik .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_nesquik.png")
        }
        .fruit-item-oranges .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_oranges.png")
        }
        .fruit-item-pasta .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_pasta.png")
        }
        .fruit-item-peas .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_peas.png")
        }
        .fruit-item-pizza_x_3 .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_pizza_x_3.png")
        }
        .fruit-item-porridge .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_porridge.png")
        }
        .fruit-item-processed_cheese .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_processed_cheese.png")
        }
        .fruit-item-reduced_fat_milk .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_reduced_fat_milk.png")
        }
        .fruit-item-regular_milk .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_regular_milk.png")
        }
        .fruit-item-rice .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_rice.png")
        }
        .fruit-item-sausage .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_sausage.png")
        }
        .fruit-item-skim_milk .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_skim_milk.png")
        }
        .fruit-item-soda .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_soda.png")
        }
        .fruit-item-sushi .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_sushi.png")
        }
        .fruit-item-tuna .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_tuna.png")
        }
        .fruit-item-water .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_water.png")
        }
        .fruit-item-white_loaf .fruit-item-icon {
            background-image: url("./img/meal/meal_plan_white_loaf.png")
        }
        .fruit-pagination {
        }
        .fruit-dots {
            margin: 10px 0 0;
        }
        .fruit-dot {
            display: inline-block;
            margin: 0 5px;
            width: 12px;
            height: 12px;
            background-color: #fcee2d;
            border-radius: 50%;
            cursor: pointer;
        }
        .fruit-dot-active {
            background-color: rgb(246, 246, 246);
        }
        .meal-planner-container .quiz-nav-container {
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="page-wrapper">
    <div class="header-wrapper">
        <div class="header">
            <div class="home-link" href="#">SNAP</div>
            <div class="settings">
                <div class="setting-icon dropdown">
                    <ul class="dropdown-menu">
                        <li class="dropdown-item"><a href="#">Logout</a></li>
                    </ul>
                </div>
                <a href="#" class="setting-text">NoButSrsly</a>
            </div>
        </div>
    </div>
    
    <div class="content-wrapper">

        <div class="meal-planner-container">

            <div class="meal-planner-header">
                <div class="h2 meal-planner-title">Meal Planner</div>
                <div class="p1 meal-planner-intro">Design the breakfast, lunch and dinner that you would like to have on any particular day: You can use any combination of the different food and categories below.</div>
            </div>

            <div class="meal-planner-content">
                <div class="mini-row">
                    <div class="col-6">
                        <div class="meal-item" data-id="breakfast">
                            <h2 class="h4 meal-name">Breakfast</h2>
                            <div class="meal-content"></div>
                        </div>
                        <div class="meal-item" data-id="lunch">
                            <h2 class="h4 meal-name">Lunch</h2>
                            <div class="meal-content"></div>
                        </div>
                        <div class="meal-item" data-id="dinner">
                            <h2 class="h4 meal-name">Dinner</h2>
                            <div class="meal-content"></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="fruit-content">
                            <div class="fruit-panel mini-row fruit-panel-active">
                                <div class="fruit-title h4">Fruit and Vegtables</div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-apples" data-id="1" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Apples
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-leafy_salad" data-id="2" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Undressed Leafy Salad
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-broccoli" data-id="3" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Broccoli
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-carrot" data-id="4" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Carrots
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-oranges" data-id="5" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Oranges
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-peas" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Peas
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-boiled_potato" data-id="7" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Boiled Potato
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-banana" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Banana
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="fruit-panel mini-row">
                                <div class="fruit-title h4">Fruit and dsa</div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-rice">
                                            <span class="fruit-item-icon"></span>
                                            Boilded Rice
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-multigrain_bread">
                                            <span class="fruit-item-icon"></span>
                                            Grain Bread
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-white_loaf" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            White Bread
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-corn_flakes" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Corn Flakes
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-instant_noodles" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Instant Noodles
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-pasta" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Plain Boiled Pasta
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-porridge" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Porridge
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-muesli" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Untoasted Muesli
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="fruit-panel mini-row">
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-fried_rice">
                                            <span class="fruit-item-icon"></span>
                                            Fried Rice
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-fries">
                                            <span class="fruit-item-icon"></span>
                                            Fries or Chips
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-meat_pie" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Meat Pie
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-sushi" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            3x Sushi Rolls
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-caesar_salad" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Vegetarian Caesar Salad
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-chicken_doner_kebab" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Chicken Doner Kebab
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-pizza_x_3" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            3x Pizza Slices
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-fast_food_burger" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Fast Food Burger
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="fruit-panel mini-row">
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-cheddar_cheese">
                                            <span class="fruit-item-icon"></span>
                                            Cheese
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-cream">
                                            <span class="fruit-item-icon"></span>
                                            Cream
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-low_fat_yogurt" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Low Fat Yogurt
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-light_sour_cream" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Light Sour Cream
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-skim_milk" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Skim Milk
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-reduced_fat_milk" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Reduced Fat Milk
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-regular_milk" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Regular Milk
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-processed_cheese" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Processed Cheese
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="fruit-panel mini-row">
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-beef_stirfry">
                                            <span class="fruit-item-icon"></span>
                                            Beef Stirfry
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-chicken_drumstick">
                                            <span class="fruit-item-icon"></span>
                                            Chicken Drumsticks
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-chicken_wings" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Chicken Wings
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-crumbed_fish" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Crumbed Fish
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-egg" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Eggs
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-lamb_roast" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Lamb Roast
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-sausage" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Sausages
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-tuna" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Tuna in Brine
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="fruit-panel mini-row">
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-water">
                                            <span class="fruit-item-icon"></span>
                                            Water
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-coffee">
                                            <span class="fruit-item-icon"></span>
                                            Instant Coffee
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-iced_tea" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Iced Tea
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-juice" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Orange Juice
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-milk_glass" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Milk
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-soda" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Soft Drink
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-nesquik" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Milo Powder
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="fruit-item-container">
                                        <div class="fruit-item fruit-item-milo" data-id="6" draggable="true">
                                            <span class="fruit-item-icon"></span>
                                            Flavoured Milk Powder
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="fruit-pagination">
                            <div class="fruit-dots">
                                <span class="fruit-dot"></span>
                                <span class="fruit-dot"></span>
                                <span class="fruit-dot"></span>
                                <span class="fruit-dot"></span>
                                <span class="fruit-dot"></span>
                                <span class="fruit-dot"></span>
                            </div>

                            <div class="quiz-nav-container">
                                <span class="quiz-nav-prev quiz-nav"></span>
                                <span class="quiz-nav-next quiz-nav"></span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>



            <div class="form-container">
                <form class="question-form">
                    <button type="submit" class="question-submit">
                        <span class="question-submit-icon"></span>
                        Submit
                    </button>
                </form>
            </div>

        </div>




    </div>


    <div class="footer-wrapper">
        <div class="footer">
            <div class="footer-content">
                <a href="#" class="footer-logo"></a>
                <ul class="footer-nav">
                    <li class="footer-nav-item"><a href="#">Any Legal Stuff</a></li>
                    <li class="footer-nav-item"><a href="#">Acknowledgements</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="./js/snap.js"></script>
<script>

    var DragCtrl = {
        init: function () {
            this.cacheElements()
            this.addListeners()
        },
        cacheElements: function () {
            this.$mealItems = $('.meal-content')
            this.$fruitContainers = $('.fruit-item-container')
            this.$fruitItems = $('.fruit-item')
        },
        addListeners: function () {
            var that = this

            this.$fruitItems.on('dragstart', function (e) {
                that.srcItem = e.currentTarget
            })
            this.$mealItems.on('dragover', function (e) {
                e.preventDefault()
            })
            this.$fruitContainers.on('dragover', function (e) {
                e.preventDefault()
            })
            this.$fruitContainers.on('drop', function (e) {
                if (!that.srcItem) {
                    return
                }
                if (!e.currentTarget.children.length) {
                    e.currentTarget.appendChild(that.srcItem)
                    that.srcItem = null
                }
            })
            this.$mealItems.on('drop', function (e) {
                if (that.srcItem) {
                    e.currentTarget.appendChild(that.srcItem)
                    that.srcItem = null
                }
            })
        }
    }
    DragCtrl.init()


    var PaginationCtrl = {
        cls: {
            panelActive: 'fruit-panel-active',
            dotActive: 'fruit-dot-active'
        },
        init: function (opt) {
            this.opt = $.extend({
                index: 0
            }, opt)
            this.cacheElements()
            this.addListeners()
            this.activeItem(this.opt.index)
        },
        cacheElements: function () {
            var $pagination = $('.fruit-pagination')
            this.$pagination = $pagination
            this.$dots = $pagination.find('.fruit-dot')
            this.$panels = $('.fruit-panel')
        },
        addListeners: function () {
            var that = this
            this.$pagination.on('click', '.fruit-dot', function (e) {
                that.activeItem(that.$dots.index(e.currentTarget))
            })
            this.$pagination.on('click', '.quiz-nav-prev', function () {
                if (that.opt.index > 0) {
                    that.activeItem(that.opt.index - 1)
                }
            })
            this.$pagination.on('click', '.quiz-nav-next', function () {
                if (that.opt.index < that.$panels.length - 1) {
                    that.activeItem(that.opt.index + 1)
                }
            })
        },
        activeItem: function (index) {
            this.opt.index = index
            this.$dots.removeClass(this.cls.dotActive)
                .eq(index)
                .addClass(this.cls.dotActive)
            this.$panels.removeClass(this.cls.panelActive)
                .eq(index)
                .addClass(this.cls.panelActive)
        }
    }
    PaginationCtrl.init()

    var FormCtrl = {
        init: function (opt) {
            this.opt = $.extend({
                onSubmit: $.noop
            }, opt)
            this.cacheElements()
            this.addListeners()
        },
        cacheElements: function () {
            this.$form = $('.question-form')
            this.$activityItems = $('.meal-item')
        },
        addListeners: function () {
            var that = this
            this.$form.on('submit', function (e) {
                e.preventDefault()
                that.opt.onSubmit(that.getData())
            })
        },
        getData: function () {
            var data = []
            this.$activityItems.each(function () {
                var $item = $(this)
                var datum = {
                    id: $item.data('id'),
                    answers: []
                }
                $item.find('.fruit-item')
                    .each(function () {
                        var $answer = $(this)
                        datum.answers.push($answer.data('id'))
                    })
                data.push(datum)
            })
            return data
        }
    }
    FormCtrl.init({
        onSubmit: function (data) {
            console.log(data)
        }
    })



</script>
</body>
</html>

