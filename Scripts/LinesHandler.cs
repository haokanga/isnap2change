using UnityEngine;
using System.Collections;
using System.Collections.Generic;

public class LinesHandler : MonoBehaviour
{
	List<Vector3> myPoints;

	public Color c1 = Color.yellow;
	public Color c2 = Color.red;
	private GameObject lineGO;
	private LineRenderer lineRenderer;
	List<BoxCollider> linecolliders;
	public GameObject lineTip;
	GameObject lt;


	void Start ()
	{

		myPoints = new List<Vector3> ();
		linecolliders = new List<BoxCollider> ();

		lineGO = new GameObject ("Line");
		lineGO.transform.localScale = new Vector3 (1, 1, 50); //depth of line = 100
		lineGO.AddComponent<LineRenderer> ();
		lineRenderer = lineGO.GetComponent<LineRenderer> ();
		lineRenderer.material = new Material (Shader.Find ("Mobile/Particles/Additive"));
		lineRenderer.SetColors (c1, c2);
		lineRenderer.SetWidth (0.2F, 0);
		lineRenderer.SetVertexCount (0);
	}
			
	void Update ()
	{

		if (myPoints.Count > 0) {
			lineRenderer.SetVertexCount (myPoints.Count);
			for (int i = 0; i<myPoints.Count; i++) {
				lineRenderer.SetPosition (i, myPoints [i]);    
			}
		} else
			lineRenderer.SetVertexCount (1);
		
		if (Input.GetMouseButton (0)) {

			//lt = Instantiate (lineTip, Camera.main.ScreenToWorldPoint (Input.mousePosition), Quaternion.identity) as GameObject;
			//InvokeRepeating ("AddPoint", .02f, .02f); 
			//InvokeRepeating ("RemovePoint", 0.3f, 0.02f);
			AddPoint();
			Invoke ("RemovePoint", 0.5f);
		} else {

			RemovePoint();
		}

		if (Input.GetMouseButtonDown (0)) {
			lt = Instantiate (lineTip, Camera.main.ScreenToWorldPoint (Input.mousePosition), Quaternion.identity) as GameObject;

		}


		if (Input.GetMouseButtonUp (0)) {

			lt.GetComponent<LinePoint>().DestroyTheTip();

			/*
			BoxCollider[] lineColliders = lineGO.GetComponents<BoxCollider> ();
			foreach (BoxCollider b in lineColliders) {
				Destroy (b);
			}
			*/

			CancelInvoke ("AddPoint");
			//myPoints.Clear ();
		}

	}




	private void RemovePoint ()
	{

		if(myPoints.Count>0)
		myPoints.RemoveAt (0);
		//Destroy (linecolliders [0]);
		//linecolliders.RemoveAt (0);
	}
	
	private void AddPoint ()
	{

		
		Vector3 tempPos = new Vector3 ();
		tempPos = Input.mousePosition;
		tempPos.z = 13;




		myPoints.Add (Camera.main.ScreenToWorldPoint (tempPos));

	
	
		/*
			BoxCollider bc = lineGO.AddComponent<BoxCollider> ();
			bc.transform.position = Camera.main.ScreenToWorldPoint (tempPos);
			bc.size = new Vector3 (0.1f, 0.1f, 0.1f);
			linecolliders.Add (bc);
		*/


		//Debug.Log ("Number of points: " + myPoints.Count);
	}

}