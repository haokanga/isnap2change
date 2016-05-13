using UnityEngine;
using System.Collections;
using System;
using System.Text.RegularExpressions;

public class LevelData : MonoBehaviour
{
    private static bool DEBUG_MODE = false;
    private static bool LOCAL_TEST = true;
    private static string retrieveScoreURL = null;
    //for LOCAL_TEST
    //private string retrieveScoreURL = "http://localhost:8080/isnap2change/isnap2change/retrieve-stored-score.php?";
    //for DEPLOYMENT     
    //private string retrieveScoreURL = "http://localhost/isnap2change/retrieve-stored-score.php?";
    public static int NUM_OF_LEVEL = 5;
    private static string retrieveScorePage = "retrieve-stored-score.php";

    //determining whether a level has been played yet or not to decide whether to show instructions
    //**********eventually pull this data from a Database**********
    public static LevelData instance = null;

    bool classic = false;
    bool timeAttack = false;
    bool ciggieBlast = false;
    bool timeMania = false;
    bool wildWest = false;

    //highscores for each level
    public int classicHS = 0;
    public int timeAttHS = 0;
    public int ciggieHS = 0;
    public int timeManHS = 0;
    public int wildWestHS = 0;

    public bool[] levels = new bool[10];

    void Awake()
    {

        if (instance == null)
        {
            instance = this;
        }
        else if (instance != this)
        {
            Destroy(gameObject);
        }
    }

    void Start()
    {
        if (retrieveScoreURL == null)
        {
            if (LOCAL_TEST) retrieveScoreURL = "http://localhost:8080/isnap2change/isnap2change/" + retrieveScorePage + "?";
            else retrieveScoreURL = "http://localhost/isnap2change/" + retrieveScorePage + "?";
        }

        levels[0] = true; //true as instructions arent relevant here
        levels[1] = classic;
        levels[2] = timeAttack;
        levels[3] = ciggieBlast;
        levels[4] = timeMania;
        levels[5] = wildWest;

        StartCoroutine(retrieveSavedScore());
        //initialize all highscores to zero, **********this is where we will pull scores from database**********       
        
        GameObject.DontDestroyOnLoad(gameObject);
        //Application.LoadLevel (1);

    }

    public int[] getHSArray()
    {
        int[] HSArray = new int[NUM_OF_LEVEL];
        HSArray[0] = classicHS;
        HSArray[1] = timeAttHS;
        HSArray[2] = ciggieHS;
        HSArray[3] = timeManHS;
        HSArray[4] = wildWestHS;
        /**
        if (DEBUG_MODE)
        {
            foreach (int s in HSArray)
            {
                Debug.Log("HSArray[]:" + s);
            }
        }
        */
        return HSArray;
    }

    private void setHS(int highscore, int index)
    {
        switch (index)
        {
            case 0:
                classicHS = highscore;
                return;
            case 1:
                timeAttHS = highscore;
                return;
            case 2:
                ciggieHS = highscore;
                return;
            case 3:
                timeManHS = highscore;
                return;
            case 4:
                wildWestHS = highscore;
                return;
            default:
                Debug.Log("[FAIL] Failed to set HS with index: " + index);
                return;
        }
    }

    IEnumerator retrieveSavedScore()
    {
        string get_url = retrieveScoreURL + "command=retrieve&gameid=1";
        if (DEBUG_MODE)
        {
            Debug.Log("[INFO] get_url:\t" + get_url);
        }
        // Post the URL to the site and create a download object to get the result.
        WWW retrieved_result = new WWW(get_url);
        yield return retrieved_result; // Wait until the download is done

        if (retrieved_result.error != null)
        {
            Debug.Log("[FAIL] There was an error retrieving score: " + retrieved_result.error);
        }
        else
        {
            if (DEBUG_MODE)
            {
                Debug.Log("[INFO] Page retrieved:\t" + retrieved_result.text);
            }
            string pattern = @"score array:(.*)\z";
            string[] retrievedText = null;
            foreach (Match match in Regex.Matches(retrieved_result.text, pattern))
            {
                if (DEBUG_MODE)
                {
                    Debug.Log("[INFO] Score retrieved:\t" + match.Value);
                }
                retrievedText = match.Value.Split(':')[1].Split(',');
            }
            if (retrievedText.Length != NUM_OF_LEVEL)
            {
                Debug.Log("[FAIL] Score Data Length Incorrect:\t" + retrievedText.Length);
            }
            else
            {
                for (int i = 0; i < NUM_OF_LEVEL; i++)
                {
                    setHS(Int32.Parse(retrievedText[i]), i);
                }
                classic = classicHS != 0 ? true : false;
                timeAttack = timeAttHS != 0 ? true : false;
                ciggieBlast = ciggieHS != 0 ? true : false;
                timeMania = timeManHS != 0 ? true : false;
                wildWest = wildWestHS != 0 ? true : false;
            }
        }
    }

    // Update is called once per frame
    void Update()
    {
        if (Input.GetKeyDown(KeyCode.M))

            if (AudioListener.volume == 0)
            {
                AudioListener.volume = 1.0f;
            }
            else {
                AudioListener.volume = 0.0f;
            }
    }
}
