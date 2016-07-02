using System;
using UnityEngine;

public class LevelData : MonoBehaviour
{
    private GameObject levelTrackerRef;
    /* DatabaseHandler */
    private DatabaseHandler databaseHandler;

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
        //get level data from the leveltracker
        levelTrackerRef = GameObject.Find("leveltracker");
        /* DatabaseHandler init */
        databaseHandler = levelTrackerRef.AddComponent<DatabaseHandler>();

        /* retrieve high score from database */
        StartCoroutine(databaseHandler.getSavedScore((storedHSArray) =>
        {
            //lambda function to process retrievedText
            for (int i = 0; i < databaseHandler.getNumOfLevel(); i++)
            {
                setHS(Int32.Parse(storedHSArray[i]), i);
            }
            classic = classicHS != 0 ? true : false;
            timeAttack = timeAttHS != 0 ? true : false;
            ciggieBlast = ciggieHS != 0 ? true : false;
            timeMania = timeManHS != 0 ? true : false;
            wildWest = wildWestHS != 0 ? true : false;
            levels[0] = true; //true as instructions arent relevant here
            levels[1] = classic;
            levels[2] = timeAttack;
            levels[3] = ciggieBlast;
            levels[4] = timeMania;
            levels[5] = wildWest;
        }));

        //initialize all highscores to zero, **********this is where we will pull scores from database********** 
      
        GameObject.DontDestroyOnLoad(gameObject);
        //Application.LoadLevel (1);

    }

    public int[] getHSArray()
    {
        return new int[] { classicHS,timeAttHS, ciggieHS, timeManHS, wildWestHS };
    }

    private void setHS(int highscore, int index)
    {
        if (getHSArray()[index] >= highscore) return;
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
