using UnityEngine;
using System.Collections;

public class Mute : MonoBehaviour {

	// Use this for initialization
	void Start () {
	
	}
	
	// Update is called once per frame
	void Update () {
	
	}

	public void mute(){ 
			
		if (AudioListener.volume == 0) {
			AudioListener.volume = 1.0f;
		} else {
			AudioListener.volume = 0.0f;
		}
	}
}
