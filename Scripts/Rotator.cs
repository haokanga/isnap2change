using UnityEngine;
using System.Collections;

public class Rotator : MonoBehaviour {
	
	private Vector3 rotation;

	void Start(){

		rotation = new Vector3 (Random.Range (0, 15), Random.Range (10, 20), Random.Range (10, 45));
	}
	// Update is called once per frame
	void Update () {
		transform.Rotate(rotation * Time.deltaTime * 3);
	}

	public void stopRotating(){
		rotation = new Vector3 (0, 0, 0);
	}
}
