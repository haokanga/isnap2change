using UnityEngine;
using System.Collections;
using UnityEngine.UI;

public class highScores : MonoBehaviour
{
    private static bool DEBUG_MODE = true;
    private static bool LOCAL_TEST = true;
    // Edit this value and make sure it's the same as the one stored on the server
    private string secretKey = "ISNAPSecretKey";
    private static string uploadScoreURL = null;
    //for LOCAL_TEST
    //private string uploadScoreURL = "http://localhost:8080/isnap2change/isnap2change/upload-score.php?";
    //for DEPLOYMENT     
    //private string uploadScoreURL = "http://localhost/isnap2change/upload-score.php?";
    private static string uploadScorePage = "upload-score.php";
    int[] scoreArray;

    //reference to level tracker to pull highscore data
    private GameObject levelTrackerRef;

    //highscore text for each game mode
    public Text classic;
    public Text timeAttack;
    public Text ciggieBlast;
    public Text timeMania;
    public Text wildWest;


    // Use this for initialization
    void Start()
    {
        if (uploadScoreURL == null)
        {
            if (LOCAL_TEST) uploadScoreURL = "http://localhost:8080/isnap2change/isnap2change/" + uploadScorePage + "?gameid=1&";
            else uploadScoreURL = "http://localhost/isnap2change/" + uploadScorePage + "?gameid=1&";
        }
        //get level data from the leveltracker
        levelTrackerRef = GameObject.Find("leveltracker");
        scoreArray = levelTrackerRef.GetComponent<LevelData>().getHSArray();
        StartCoroutine(updateScore());
    }

    IEnumerator updateScore()
    {
        if (DEBUG_MODE)
        {
            //Debug.Log("[INFO] updateScore()");
        }
        string scoreListSequence = "";
        foreach (int s in scoreArray)
        {
            scoreListSequence += "score[]=" + s + "&";
        }
        scoreListSequence = scoreListSequence.Substring(0, scoreListSequence.Length - 1);

        string get_url = uploadScoreURL + scoreListSequence;
        //string get_url = uploadScoreURL + "score[]=0&score[]0&score[]=0&score[]=0&score[]=0";

        if (DEBUG_MODE)
        {
            //Debug.Log("[INFO] get_url:\t" + get_url);
        }
        // Post the URL to the site and create a download object to get the result.
        WWW upload_result = new WWW(get_url);
        yield return upload_result; // Wait until the download is done

        if (upload_result.error != null)
        {
            Debug.Log("[FAIL] There was an error uploading score: " + upload_result.error);
        }
        else
        {
            //Debug.Log("[SUCCESS] Score upload successfully.");
        }
    }



    // Update is called once per frame
    void Update()
    {
        //update scoreArray
        scoreArray = levelTrackerRef.GetComponent<LevelData>().getHSArray();

        classic.text = scoreArray[0] + "\nPoints";
        timeAttack.text = scoreArray[1] + "\nPoints";
        ciggieBlast.text = scoreArray[2] + "\nPoints";
        timeMania.text = scoreArray[3] + "\nPoints";
        wildWest.text = scoreArray[4] + "\nPoints";
    }
}
