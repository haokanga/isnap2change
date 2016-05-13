using UnityEngine;
using System.Collections;
using UnityEngine.UI;
using System.Collections.Generic;

public class Food : MonoBehaviour
{
    

	[SerializeField]

	private Animator
		anim;
	private GameObject
		splashReference;
	public GameObject welldoneRef;
	private Vector3 randomPos;
	private int collisions = 0;
	private Text scoreReference;

	//get objects rigid body
	public Rigidbody rb;
	public GameObject smoke;
	public GameObject smoke2;
	public static List<GameObject> swipeList = new List<GameObject> ();

	//to check if object is at highest point
	int oldYPos = -1000;
	int newYPos;
	bool messageShown = false;


	//the index for each level
	int classic = 1;
	int timeattack = 2;
	int ciggieblast = 3;
	int timemania = 4;
	int wildwest = 5;

	void Awake ()
	{
	

		anim = Camera.main.GetComponent<Animator> ();
	}

	void Start ()
	{
		rb = gameObject.GetComponent<Rigidbody> ();
		scoreReference = GameObject.Find ("Score").GetComponent<Text> ();
	}

	IEnumerator ScaleOverTime (float time)
	{
		rb.constraints = RigidbodyConstraints.FreezePositionY;
		rb.constraints = RigidbodyConstraints.FreezePositionX;
		Vector3 originalScale = gameObject.transform.localScale;
		Vector3 destinationScale = new Vector3 (0.1f, 0.1f, 0.1f);
		
		float currentTime = 0.0f;
		
		do {
			gameObject.transform.localScale = Vector3.Lerp (originalScale, destinationScale, currentTime / time);
			currentTime += Time.deltaTime;
			yield return null;
		} while (currentTime <= time);
		
		Destroy (gameObject);
	}
    
	void Update ()
	{
		//Remove food if out of view 
		if (gameObject.transform.position.y < -36) {
			Destroy (gameObject);
		}

		newYPos = (int)gameObject.transform.position.y;

		if (newYPos < oldYPos && gameObject.tag == "destroyer" && !messageShown && Application.loadedLevel != ciggieblast) {
			Debug.Log ("show message");
			GameManager.instance.ShowNegFeedback(new Vector3(gameObject.transform.position.x, gameObject.transform.position.y, 1));
			messageShown = true;
		}

		oldYPos = newYPos;
	}

	public void RemoveObject ()
	{
		StartCoroutine (ScaleOverTime (0.15f));
	}

	public void boost ()
	{

		Vector3 throwForce = new Vector3 (Random.Range (-15, 15), 40, 0);
		gameObject.GetComponent<Rigidbody> ().AddForce (throwForce, ForceMode.Impulse);
		GameManager.instance.playSound ("blast");
		Invoke ("RemoveObject", 2);
	}



	void OnTriggerEnter (Collider other)
	{
		if (other.gameObject.tag == "linetip" && collisions < 1) {
	
			//Chuck a switch in here because we are going to encounter many different objects

			string collidedObject = gameObject.tag;

			if (!collidedObject.Equals ("sportball")) {
				RemoveObject ();
				//Destroy(gameObject)
			}


			switch (collidedObject) {
			case("sportball"): 
				GameManager.instance.addPoints (1);
				GameManager.instance.playSound ("splat");
				swipeList.Add (gameObject);
				GameManager.instance.StartTimer ();
				//GameManager.instance.ShowFeedback(1,gameObject.transform.position, true); //too much?
					//Instantiate (welldoneRef, gameObject.transform.position, Quaternion.identity);
				break;
			case("blocker"): 
				GameManager.instance.deductPoints (30);
				GameManager.instance.playSound ("error");
				iTween.ShakeRotation (Camera.main.gameObject, new Vector3 (1, 1, 0), 0.75f);
				GameManager.instance.ShowFeedback (12, gameObject.transform.position, true);
				break;
			case("alcohol"): 
				GameManager.instance.deductPoints (30);
				GameManager.instance.playSound ("glass");
				iTween.ShakeRotation (Camera.main.gameObject, new Vector3 (3, 3, 0), 0.75f);
				GameManager.instance.ShowFeedback (12, gameObject.transform.position, false);
				GameManager.instance.ShowFeedback (23, gameObject.transform.position, true);
				anim.SetBool ("blur", true);
				GameManager.instance.Invoke("TurnOffBlur", 6);

				break;
			case("fruit"): 
				GameManager.instance.addPoints (10);
				GameManager.instance.playSound ("splat");

				//if in time mania mode, add 1 second to clock
				if(Application.loadedLevel == timemania){
					GameObject.Find("time").GetComponent<Timer>().addSeconds(1);
					GameManager.instance.ShowFeedback(26, gameObject.transform.position,false);
				}
				else{
					GameManager.instance.ShowFeedback (Random.Range (13, 16), gameObject.transform.position, true);
				}

				break;
			case("destroyer"): 
				GameManager.instance.playSound ("error");
				GameManager.instance.playSound ("boo");
				//GameManager.instance.ShowNegFeedback(new Vector3(gameObject.transform.position.x, gameObject.transform.position.y +1, 1));
				iTween.ShakeRotation (Camera.main.gameObject, new Vector3 (1, 1, 0), 0.75f);

				//halve points on ciggie blast mode
				if(Application.loadedLevel == ciggieblast){
					if (int.Parse (scoreReference.text) > 1) {
						Instantiate (smoke2, new Vector3 (0, -10.5f, -4f), smoke.transform.rotation);
						GameManager.instance.halvePoints ();
					}
				}
				//show smoke on wild west for shorter period
				else if(Application.loadedLevel == wildwest){
					Instantiate (smoke2, new Vector3 (0, -10.5f, -4f), smoke.transform.rotation);
					GameManager.instance.changeScoreToZero();
				}
				//end game only if on classic mode
				else if (Application.loadedLevel == classic){
					if (int.Parse (scoreReference.text) > 1) {
						//GameManager.instance.halveScoreImmediate();
						GameManager.instance.halvePoints();
					}
					Instantiate (smoke, new Vector3 (0, -10.5f, -4f), smoke.transform.rotation);
					GameManager.instance.GameOverCig ();
				}
				//if in time mania mode take 2 seconds off clock
				else if(Application.loadedLevel == timemania){
					Instantiate (smoke, new Vector3 (0, -10.5f, -4f), smoke.transform.rotation);
					GameObject.Find("time").GetComponent<Timer>().removeSeconds(2);
					GameManager.instance.ShowFeedback(25, gameObject.transform.position,true);
					GameManager.instance.changeScoreToZero();
				}
				//show smoke on all other levels for longer
				else if(Application.loadedLevel == timeattack){
					Instantiate (smoke, new Vector3 (0, -10.5f, -4f), smoke.transform.rotation);
					GameManager.instance.changeScoreToZero();
				}




				break;
			case("waterbottle"):
				GameManager.instance.doublePointsMode ();
				GameManager.instance.playSound ("splat");
                if(Application.loadedLevel == timemania){
					GameObject.Find("time").GetComponent<Timer>().addSeconds(5);
					GameManager.instance.ShowFeedback (24, gameObject.transform.position, true);
				} else {                    
                    GameManager.instance.addPoints (1);
                }
				
	
				break;
			default: 
				GameManager.instance.addPoints (1);
				GameManager.instance.playSound ("splat");
				break;

			}

			collisions++;
			Debug.Log (collisions);
		}

	}	
}