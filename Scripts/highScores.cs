using UnityEngine;
using UnityEngine.UI;

public class highScores : MonoBehaviour
{
    int[] scoreArray;

    //reference to level tracker to pull highscore data
    private GameObject levelTrackerRef;

    //highscore text for each game mode
    public Text classic;
    public Text timeAttack;
    public Text ciggieBlast;
    public Text timeMania;
    public Text wildWest;

    /* DatabaseHandler */
    private DatabaseHandler databaseHandler;


    // Use this for initialization
    void Start()
    {
        //get level data from the leveltracker
        levelTrackerRef = GameObject.Find("leveltracker");
        scoreArray = levelTrackerRef.GetComponent<LevelData>().getHSArray();

        /* DatabaseHandler init */
        databaseHandler = levelTrackerRef.AddComponent<DatabaseHandler>();

        /*  update high score to database */
        StartCoroutine(databaseHandler.updateScore(scoreArray));
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
