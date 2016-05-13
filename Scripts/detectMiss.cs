using UnityEngine;
using System.Collections;
using UnityEngine.UI;

public class detectMiss : MonoBehaviour {

	int lives;
	public GameObject floor;
	public Text livesText;
	// Use this for initialization
	void Start () {
		lives = 5;
		livesText.text = "" + lives;
	}
	
	// Update is called once per frame
	void Update () {
	
	}

	void OnTriggerEnter(Collider other){

		if (other.gameObject.CompareTag ("sportball") && lives > 0) {

			lives--;

			livesText.text = "" + lives;

			if(lives <= 0){
				 GameManager.instance.GameOverMiss();
			}

			Vector3 errorPos = new Vector3(other.gameObject.transform.position.x, -2.71f, other.gameObject.transform.position.z);

			Debug.Log("hamburger missed!" + lives);
			GameManager.instance.ShowFeedback(11, errorPos, false);
			GameManager.instance.playSound("oops");
		}

	}	
}
