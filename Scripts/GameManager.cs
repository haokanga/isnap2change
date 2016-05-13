using UnityEngine;
using System.Collections;
using UnityEngine.UI;
using System.Collections.Generic;

public class GameManager : MonoBehaviour
{

	//double points mode
	public bool doublepoints = false;
	private Text scoreReference;
	private Text finalScoreRef;
	public static GameManager instance = null;

	//points
	int highscore;
	public int points = 0;
	int cheerInterval = 50;
	public bool gameEnded = false;
	bool newHighScore;


	//sound effects
	public AudioClip boo;
	public AudioClip glass;
	public AudioClip oops;
	public AudioClip error;
	public AudioClip splat;
	public AudioClip cheer;
	public AudioClip ticking;
	public AudioClip scoredrop;
	public AudioClip blast;

	//audio sources
	private AudioSource errorSource;
	private AudioSource tickingSource;
	private AudioSource blastSource;


	//for feedback text during the game
	public GameObject[] feedback;
	public GameObject[] negFeedback;


	//clock UI
	//public GameObject clockRef;
	public GameObject clock;

	//gameover screen
	public GameObject holder;
	public GameObject cigGameover;
	public GameObject regGameover;
	public Text finalScore;
	public GameObject totalScoreReference;
	public GameObject HighScoreReference;


	//pause menu
	public GameObject pauseMenu;

	//points to be makes sure that the final score is correct when it is counting down
	int pointsToBe;

	private Animator
		anim;

	void Awake ()
	{

		if (instance == null) {
			instance = this;
		} else if (instance != this) {
			Destroy (gameObject);
		}
		errorSource = GetComponent<AudioSource> ();

		//for the double points stopwatch sound
		GameObject ticker = GameObject.FindGameObjectWithTag ("stopwatch");
		tickingSource = ticker.GetComponent<AudioSource> ();

		//for the mutiple ball explosion
		blastSource = GetComponent<AudioSource> ();
		blastSource.clip = blast;

		//make background sounds continue when game is paused
		GameObject.Find ("Background").GetComponent<AudioSource> ().ignoreListenerPause = true;

		//for the blur animation
		anim = Camera.main.GetComponent<Animator> ();
	}

	void Start ()
	{
		scoreReference = GameObject.Find ("Score").GetComponent<Text> ();
		finalScoreRef = GameObject.Find ("finalscore").GetComponent<Text> ();
		highscore = getHighScoreForLevel (Application.loadedLevel);
		Debug.Log ("Highscore: " + highscore);

	}

	int getHighScoreForLevel (int currentLevel)
	{

		switch (currentLevel) {
		case 1:
			return LevelData.instance.classicHS;
		case 2:
			return LevelData.instance.timeAttHS;
		case 3:
			return LevelData.instance.ciggieHS;
		case 4:
			return LevelData.instance.timeManHS;
		case 5:
			return LevelData.instance.wildWestHS;
		default:
			return LevelData.instance.classicHS;
		}

	}

	void setHighScoreForLevel (int currentLevel, int highscore)
	{
		switch (currentLevel) {
		case 1:
			LevelData.instance.classicHS = highscore;
			break;
		case 2:
			LevelData.instance.timeAttHS = highscore;
			break;
		case 3:
			LevelData.instance.ciggieHS = highscore;
			break;
		case 4:
			LevelData.instance.timeManHS = highscore;
			break;
		case 5:
			LevelData.instance.wildWestHS = highscore;
			break;
		default:
			LevelData.instance.classicHS = highscore;
			break;
		}
	}

	int calculateHighScore ()
	{

		if (highscore > points) {
			newHighScore = false; // no new highscore was obtained
			return highscore;
		} else {
			newHighScore = true; // new highscore was reached
			return points;
		}
	}
	
	// Update is called once per frame
	void Update(){
		
		if (Input.GetKeyDown ("space") && Time.timeScale == 1) {
			Time.timeScale = 0;
			pauseMenu.SetActive(true);
			AudioListener.pause = true;
		} else if (Input.GetKeyDown ("space") && Time.timeScale == 0) {
			Time.timeScale = 1;
			pauseMenu.SetActive(false);
			AudioListener.pause = false;
		}
		
	}

	public void addPoints (int pts)
	{

		if (!gameEnded) {

			//if doublepoints mode is on double the points
			if (doublepoints) {

				if (points < cheerInterval && (points + (pts * 2)) >= cheerInterval) {
					playSound ("cheer");
					cheerInterval += 50;
				}

				points += pts * 2;
			} else {

				if (points < cheerInterval && (points + pts) >= cheerInterval) {
					playSound ("cheer");
					cheerInterval += 50;
					ShowFeedback (Random.Range (13, 20), new Vector3 (-1.84f, 6.22f, 2), true);//too much
					ShowFeedback (21, new Vector3 (2.29f, 6.22f, 2), true);//too much
				}
				points += pts;
			}
			scoreReference.text = "" + points;
		}
	}
	
	public void deductPoints (int pts)
	{

		if (!gameEnded) {

			if (points >= 30) {
				points -= pts;
			} else {
				points = 0;
			}
			scoreReference.text = "" + points;
		}
	}


	
	public void doublePointsMode ()
	{

		//if already in double points mode, then dont cancel the invoke in 5 seconds
		if (tickingSource.isPlaying) {
			CancelInvoke ("endDoublePointsMode");
			//Destroy (clock);
			clock.SetActive(false);
		}

		clock.SetActive (true);
		//clock = Instantiate (clockRef, new Vector3 (0, 18.19f, 25.1f), Quaternion.identity) as GameObject;
	
		tickingSource.loop = true;
		tickingSource.clip = ticking;
		tickingSource.Play ();



		Debug.Log ("-----------------Double Points Started------------------");
		doublepoints = true;
		Invoke ("endDoublePointsMode", 5);
		
	}
	
	public void endDoublePointsMode ()
	{
		doublepoints = false;
		clock.SetActive (false);
		Debug.Log ("-----------------Double Points Ended------------------");
		tickingSource.Stop ();
	}

	public void playSound (string sound)
	{

		if (sound.Equals ("error")) {
			errorSource.PlayOneShot (error);
		} else if (sound.Equals ("cheer")) {
			errorSource.PlayOneShot (cheer);
		} else if (sound.Equals ("blast")) {
			blastSource.PlayOneShot (blast);
		} else if (sound.Equals ("oops")) {
			blastSource.PlayOneShot (oops);
		} else if (sound.Equals ("glass")) {
			blastSource.PlayOneShot (glass);
		} else if (sound.Equals ("boo")) {
			blastSource.PlayOneShot (boo);
		} else {
			errorSource.PlayOneShot (splat);
		}

	}

	void DeleteObjects ()
	{

		if (Food.swipeList.Count < 2) {
			foreach (GameObject ob in Food.swipeList) {

				ob.GetComponent<Food> ().RemoveObject ();
			}

			Food.swipeList.Clear ();
		} else {



			Vector3 firstObjPos = Food.swipeList [0].transform.position;
			Vector3 lastObjPos = Food.swipeList [Food.swipeList.Count - 1].transform.position;

			int meetingPointX = ((int)firstObjPos.x + (int)lastObjPos.x) / 2;
			int meetingPointY = ((int)firstObjPos.y + (int)lastObjPos.y) / 2;


			ShowFeedback (Food.swipeList.Count, new Vector3 (meetingPointX, meetingPointY, firstObjPos.z), false);
			ShowFeedback (Random.Range (13, 21), new Vector3 (meetingPointX, meetingPointY + 3, firstObjPos.z), false); //too much?

			addPoints (Food.swipeList.Count);	//adds one bonus pt per ball in group
			
			foreach (GameObject ob in Food.swipeList) {
				iTween.MoveTo (ob, iTween.Hash ("x", meetingPointX, "y", meetingPointY, "time", 1, "oncomplete", "boost", "oncompletetarget", ob));
			}

			Food.swipeList.Clear ();
			Debug.Log ("group together and explode!");
		}
	}

	public void StartTimer ()
	{

		if (Food.swipeList.Count == 1) {
			Invoke ("DeleteObjects", 0.2f);
		}
	}

	public void GameOverCig ()
	{
		
		Time.timeScale = 0.5f;
		GameManager.instance.gameEnded = true;
		finalScoreRef.text = "" + points;
		setHighScoreForLevel (Application.loadedLevel, calculateHighScore ());

		if (!regGameover.activeInHierarchy)
			cigGameover.SetActive (true);

		if (newHighScore) {
			HighScoreReference.SetActive (true);
		} else {
			totalScoreReference.SetActive(true);
		}

		holder.gameObject.SetActive (true);


	}

	public void GameOverMiss ()
	{
		
		Time.timeScale = 0.5f;
		GameManager.instance.gameEnded = true;
		finalScoreRef.text = "" + points;
		
		GameObject.Find("Canvas").GetComponent<AudioSource> ().Play ();

		setHighScoreForLevel (Application.loadedLevel, calculateHighScore ());

		if (!cigGameover.activeInHierarchy)
			regGameover.SetActive (true);

		if (newHighScore) {
			HighScoreReference.SetActive (true);
		} else {
			totalScoreReference.SetActive(true);
		}

		holder.gameObject.SetActive (true);


	}

	public void ShowFeedback (int type, Vector3 position, bool actualPos)
	{

		if (!actualPos) {

			position.y -= 2;
		}
		position.z -= 2;

		Instantiate (feedback [type], position, Quaternion.identity);
	}

	public void ShowNegFeedback(Vector3 position){

		Instantiate (negFeedback [Random.Range (0, negFeedback.Length - 1)], position, Quaternion.identity);

	}

	public void halveScoreImmediate(){

		points = points / 2;
		scoreReference.text = "" + points;
	}

	public void halvePoints ()
	{
		
		if (!gameEnded) {
			StartCoroutine(ScoreToHalf());
		}
	}

	public IEnumerator ScoreToHalf(){

		int tempScore = points;
		points = points / 2;

		yield return new WaitForSeconds (0.1f);
		
		while (tempScore>points) {
			tempScore--;
			scoreReference.text = "" + tempScore;
			errorSource.PlayOneShot (scoredrop, 0.3f);
			yield return new WaitForSeconds (0.005f);
		}

	}

	public void changeScoreToZero(){

		if(!gameEnded)
		StartCoroutine (ScoreToZero ());

	}

	public IEnumerator ScoreToZero ()
	{
		int tempScore = points;
		points = 0;
		yield return new WaitForSeconds (0.1f);

		while (tempScore>points) {
			tempScore--;
			scoreReference.text = "" + tempScore;
			errorSource.PlayOneShot (scoredrop, 0.3f);
			yield return new WaitForSeconds (0.005f);
		}

	}	

	public void TurnOffBlur(){
		
		anim.SetBool ("blur", false);
	}
}
