using UnityEngine;
using System.Collections;
using UnityEngine.UI;

public class MenuHandler : MonoBehaviour
{

    private GameObject levelTrackerRef;
    /* DatabaseHandler */
    private DatabaseHandler databaseHandler;

    public Button[] levelbuttons;
    private AudioSource audiosource;
    public AudioClip hoverSound;
    public AudioClip cheer;

    void Start()
    {
        //get level data from the leveltracker
        levelTrackerRef = GameObject.Find("leveltracker");
        /* DatabaseHandler init */
        databaseHandler = levelTrackerRef.AddComponent<DatabaseHandler>();
        StartCoroutine(databaseHandler.getStudentGameWeek((week) =>
        {
            //lambda function to process retrieved week
            if (levelbuttons != null)
            {
                for (int i = 0; 2 * i <= week; i++)
                {
                    levelbuttons[i].interactable = true;
                }
            }


        }));

        Time.timeScale = 1;
        audiosource = gameObject.GetComponent<AudioSource>();
        AudioListener.pause = false;
        //just so these continue to play when game is paused and user returns to main menu
        //audiosource.ignoreListenerPause = true;
        //Camera.main.GetComponent<AudioSource> ().ignoreListenerPause = true;
    }

    public void LoadScene(int level)
    {

        //if the level in level tracker has not yet been played, then show instructions
        if (GameObject.Find("leveltracker").GetComponent<LevelData>().levels[level] == true)
        {
            Application.LoadLevel(level);
        }
        else {

            GameObject.Find("leveltracker").GetComponent<LevelData>().levels[level] = true;
            Application.LoadLevel(level + 6);
        }

    }

    public void Retry()
    {

        Application.LoadLevel(Application.loadedLevel);
    }

    public void LoadInstructions(int instructionScene)
    {

        Application.LoadLevel(instructionScene);

    }

    public void QuitGame()
    {

        Application.Quit();
    }


    public void PlayHoverSound()
    {

        audiosource.PlayOneShot(hoverSound);

    }



    public void PlayCheer()
    {

        LevelData.instance.GetComponent<AudioSource>().PlayOneShot(cheer);

    }

}
