using UnityEngine;
using System.Collections;
using UnityEngine.UI;

public class Instructions : MonoBehaviour {

	int currentLevel; //tells us which instruction screen we are so we can navigate to correct level
	public Text buttontext; // need this to change text of button

	public Text screenText;
	private int currentInstruction;
	public string[] classicInstructions;
	public string[] timeAttackInstructions;
	public string[] ciggieBlastInstructions;
	public string[] timeManiaInstructions;
	public string[] wildWestInstructions;

	public string[] levelsInstructions;

	// Use this for initialization
	void Start () {


		currentInstruction = -1; //as no instructions are to be shown at this point
		classicInstructions = new string[5];
		classicInstructions[0] = "A range of balls will be fired your way." +
						  "\n\nYour goal is to stay active by kicking or throwing them away." +
						  "\nYou do this by swiping across the screen holding your cursor " +
						  "\nor simply touch the screen if you have a PC with a touch screen" +
						  "\n\nEvery ball you kick or throw away is 1 point." +
						  "\n\nIf you kick or throw away multiple balls in one swipe, you get " +
						  "\nbonus points for the amount of balls you swipe.";

		classicInstructions [1] = "As part of staying healthy it is important to eat" +
								"\nenough fruit and drink enough water." +
								"\n\nThat’s why you should try and grab as many pieces of fruit as possible." +
								"\nEach piece of fruit is worth 10 points." +
								"\n\nIf a water bottle appears make sure you swipe it," +
								"\nyou'll get double the points for 5 seconds. ";

		classicInstructions[2] = "Stay away from Alcohol. Alcohol lowers your ability to perform optimally. " +
							"\n\nIf you swipe an alcohol bottle your screen will become blurry and" +
							"\nyou will lose 30 points." +
							"\n\nMake sure you collect a fruit to restore your vision back to normal.";

		classicInstructions[3] = "Cigarettes are bad." +
							"\n\nThey can negatively affect your physical activity, immediately & long term." +
							"\nThere’s no such thing as only ‘one cigarette’." +
							"\n\nThe more you progress in the game, the more cigarettes will appear." +
							"\nMake sure to avoid them." +
							"\n\nIf you slice a cigarette the game will be over!";

		classicInstructions[4] = "It’s time to play ball!" +
							"\n\n You can pause the game by pressing spacebar" +
							"\n\nTry to get as many points as possible. " +
							"\nRemember: Cigarettes and alcohol are bad for you." +
							"\nAvoid cigarettes and alcohol at all costs" +
							"\nThe game ends when you've dropped 5 balls…" +
							"\n\nLet’s go!";


		timeAttackInstructions = new string[2];

		timeAttackInstructions [0] = "It’s time to introduce a timer." +
									"\nThere’s 2 minutes on the clock. Your goal is to kick or throw away as " +
									"\nmany balls as possible within the time period," +
									"\njust like you did in the previous game." +
									"\nYou do this by swiping across the screen holding your cursor or simply " +
									"\ntouch the screen if you have a PC with a touch screen" +
									"\n\nThe usual suspects are here as well; fruit gives you bonus points and alcohol" +
									"\ncosts you 30 point each time you hit the bottle." +
									"\n\nMake sure you avoid cigarettes; they will reset your score to 0 and " +
									"\nwill cause the screen to be smoky for 5 seconds.";

		timeAttackInstructions [1] = "Cigarettes however do not cause you to game over." +
									"\nCigarettes are expensive; hitting a cigarette in this game will cost you" +
									"\nall of your points. " +
									"\n\n(press spacebar to pause game)";

		ciggieBlastInstructions = new string[1];

		ciggieBlastInstructions [0] = "In ciggie blast you need to catch as many balls as possible," +
									"\nbut beware there are cigarettes everywhere." +
									"\n\nTry to avoid them and only catch the balls." +
									"\n\nIf you touch a cigarette, your points get halved and the" +
									"\nscreen will turn smoky." +
									"\n\n(press spacebar to pause game)";

		timeManiaInstructions = new string[1];

		timeManiaInstructions [0] = "In Time mania you need to catch as many balls as possible within 60 seconds. " +
									"\nEvery time a piece of fruit comes into the screen and you slice it," +
									"\nyou will get an extra second on the clock." +
									"\n\nMake sure you avoid alcohol and cigarettes as they will cost you points." +
									"\nAlcohol will make you lose 30 points and will make the screen blurry," +
									"\ncigarettes will reset your points and will turn the screen smoky." +
									"\n\nSo it’s fairly simple: stay away from alcohol and cigarettes." +
									"\n\n(press spacebar to pause game)";

		wildWestInstructions = new string[1];

		wildWestInstructions [0] = "The wild west is all about rapid fire and so is this game. You need to catch" +
									"\nas many balls as possible within 2 minutes, but they are all over the place." +
									"\nAnd so are alcohol bottles and cigarettes. " +
									"\nThere is no such thing as just one of them." +
									"\nStay focused and avoid bad influences." +
									"\n\nThe goal is the same as in previous games: kick away the balls" +
									"\nand don't touch alcohol bottles as you will lose points. " +
									"\nTouching a cigarette will make you lose all of your points and " +
									"\n will make your vision hazy." +
									"\n\nGood luck." +
									"\n\n(press spacebar to pause game)";



		currentLevel = Application.loadedLevel;

		switch (currentLevel) {

		case 7:  levelsInstructions = classicInstructions;
			break;
		case 8:  levelsInstructions = timeAttackInstructions;
			break;
		case 9:  levelsInstructions = ciggieBlastInstructions;
			break;
		case 10:  levelsInstructions = timeManiaInstructions;
			break;
		case 11:  levelsInstructions = wildWestInstructions;
			break;
		default: levelsInstructions = classicInstructions;
			break;
		}



	}
	
	// Update is called once per frame
	void Update () {
	
	}

	public void NextClicked(){

		if (currentInstruction >= levelsInstructions.Length - 2) {
			buttontext.text = "Play";
		} else {
			buttontext.text = "Next";
		}

		if (currentInstruction >= levelsInstructions.Length - 1) {
			Application.LoadLevel (currentLevel - 6);
		} else {
			currentInstruction++;
			screenText.text = levelsInstructions [currentInstruction];
		}

	
	}
	
}
