using UnityEngine;
using System.Collections;
using System;


class DatabaseHandler : MonoBehaviour
{
    public static int NUM_OF_LEVEL = 5;

    private static bool DEBUG_MODE = false;
    private static bool LOCAL_TEST = true;
    // TODO: [Safety] Edit this value and make sure it's the same as the one stored on the server
    private string secretKey = "ISNAPSecretKey";
    private string gameHandlerURL = null;
    private string gameHandlerParameters = null;
    private string gameHandlerPage = "game-handler.php";

    public DatabaseHandler()
    {
        init();
    }

    void init()
    {
        //for LOCAL_TEST
        //private string gameHandlerURL = "http://localhost:8080/isnap2change/isnap2change/game-handler.php";
        //for DEPLOYMENT     
        //private string gameHandlerURL = "http://localhost/game-handler.php";
        if (gameHandlerURL == null)
        {
            if (LOCAL_TEST) gameHandlerURL = "http://localhost:8080/isnap2change/isnap2change/" + gameHandlerPage + "?secretKey=" + secretKey;
            else gameHandlerURL = "http://localhost/" + gameHandlerPage + "?secretKey=" + secretKey;
        }
    }

    public int getNumOfLevel() {
        return NUM_OF_LEVEL;
    }

    //use System.Action<?> to pseudo "return" result
    public IEnumerator getStudentGameWeek(Action<Int32> week)
    {
        gameHandlerParameters = "&command=get_week&gameID=1";
        string get_url = gameHandlerURL + gameHandlerParameters;
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
            week(Int32.Parse(retrieved_result.text));
        }
    }

    //use System.Action<?> to pseudo "return" result
    public IEnumerator getSavedScore(Action<String[]> storedHSArray)
    {
        gameHandlerParameters = "&command=retrieve&gameID=1";
        string get_url = gameHandlerURL + gameHandlerParameters;
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
            //pseudo "return" retrievedText
            storedHSArray(retrieved_result.text.Split(','));
        }
    }

    public IEnumerator updateScore(int[] scoreArray)
    {
        gameHandlerParameters = "&command=upload&gameID=1";

        string scoreListSequence = "";
        foreach (int s in scoreArray)
        {
            scoreListSequence += "&score[]=" + s;
        }

        string get_url = gameHandlerURL + gameHandlerParameters + scoreListSequence;
        //string get_url = gameHandlerURL + gameHandlerParameters + "score[]=0&score[]0&score[]=0&score[]=0&score[]=0";

        if (DEBUG_MODE)
        {
            Debug.Log("[INFO] get_url:\t" + get_url);
        }
        // Post the URL to the site and create a download object to get the result.
        WWW upload_result = new WWW(get_url);
        yield return upload_result; // Wait until the download is done

        if (upload_result.error != null)
        {
            Debug.Log("[FAIL] There was an error uploading score: " + upload_result.error);
        }
    }
}




