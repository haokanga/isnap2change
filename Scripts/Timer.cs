using UnityEngine;
using System.Collections;
using UnityEngine.UI;

public class Timer : MonoBehaviour
{
	public Text timeTF;
	//public GameObject alertReference;
	int time;
	int minutes;
	int seconds;

	void Start ()
	{
		Debug.Log ("Time scale is: " + Time.timeScale);

		Time.timeScale = 1;

		//60 seconds for time mania and 2 mins for other modes
		if (Application.loadedLevel == 4) {
			time = 60;
		}else {
			time = 120;
		}

		setTimerText (time);
		InvokeRepeating ("ReduceTime", 1, 1);
	}
    
	void ReduceTime ()
	{


		if (timeTF.text == "00:01") {
			Time.timeScale = 0.5f;
			GameManager.instance.GameOverMiss ();
		}

		if (time > 0) {
			time--;
			setTimerText (time);
		}
	}

	void setTimerText (int time)
	{

		//split the time into minutes and seconds
		minutes = time / 60;
		seconds = time % 60;

		if (minutes < 1) {
			if (seconds < 10) {
				timeTF.text = "00:0" + seconds;
			} else {
				timeTF.text = "00:" + seconds;
			}
		} else if(minutes < 10) {
			if (seconds < 10) {
				timeTF.text = "0" + minutes + ":0" + seconds;
			} else {
				timeTF.text = "0" + minutes + ":" + seconds;
			}
		}

		if (time <= 10) {
			timeTF.color = new Color (255, 0, 0);
		} else {
			timeTF.color = new Color (255,255,255);
		}
	}

	public void addSeconds(int seconds){

		if (!GameManager.instance.gameEnded) {
			time += seconds;
			setTimerText (time);
		}
	}

	public void removeSeconds(int seconds){
		if (!GameManager.instance.gameEnded) {
			//dont want to go in the minuses
			if(time > seconds){
				time -= seconds;
				setTimerText (time);
			}
		}
	}
}
