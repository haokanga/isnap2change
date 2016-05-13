using UnityEngine;
using System.Collections;

public class LinePoint : MonoBehaviour {

	Vector3 tempPos;
	// Use this for initialization
	void Start () {
	
	}
	
	// Update is called once per frame
	void Update () {
		tempPos = Input.mousePosition;
		tempPos.z = 13;
		gameObject.transform.position = Camera.main.ScreenToWorldPoint (tempPos);
	}

	public void DestroyTheTip(){

		Destroy (gameObject);
	}
}
