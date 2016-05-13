using UnityEngine;
using System.Collections;

public class SmokeControl : MonoBehaviour {

	// Use this for initialization
	void Start () {

		Invoke ("RemoveSmokeObject", 12);
	
	}

	void RemoveSmokeObject(){

		Destroy (gameObject);
	}

	// Update is called once per frame
	void Update () {
	
	}
}
