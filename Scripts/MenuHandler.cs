using UnityEngine;
using System.Collections;

public class MenuHandler : MonoBehaviour {

	private AudioSource audiosource;
	public AudioClip hoverSound;
	public AudioClip cheer;


	void Start(){
		Time.timeScale = 1;
		audiosource = gameObject.GetComponent<AudioSource> ();
		AudioListener.pause = false;
		//just so these continue to play when game is paused and user returns to main menu
		//audiosource.ignoreListenerPause = true;
		//Camera.main.GetComponent<AudioSource> ().ignoreListenerPause = true;
	}

	public void LoadScene(int level){

		//if the level in level tracker has not yet been played, then show instructions
		if (GameObject.Find ("leveltracker").GetComponent<LevelData> ().levels[level] == true) {
			Application.LoadLevel (level);
		} else {

			GameObject.Find ("leveltracker").GetComponent<LevelData> ().levels[level] = true;
			Application.LoadLevel (level+6);
		}

	}

	public void Retry(){

		Application.LoadLevel (Application.loadedLevel);
	}

	public void LoadInstructions (int instructionScene){

		Application.LoadLevel (instructionScene);

	}

	public void QuitGame(){

		Application.Quit ();
	}


	public void PlayHoverSound(){

		audiosource.PlayOneShot (hoverSound);

	}



	public void PlayCheer(){
	
		LevelData.instance.GetComponent<AudioSource> ().PlayOneShot (cheer);
	
	}

}
