# POST 
curl -X POST -u 6678c2a8-0261-44f8-a9d3-9a2fb43d65b6:40byApeYUV7f --header "Content-Type: application/json" --header "Accept: audio/wav" --data "{\"name\": \"en-US_AllisonVoice\",\"language\":\"en_US\",\"text\":\"Eating a balanced diet is vital for your health and wellbeing. The food we eat is responsible for providing us with the energy to do all the tasks of daily life. For optimum performance and growth a balance of protein, essential fats, vitamins and minerals are required. We need a wide variety of different foods to provide the right amounts of nutrients for good health. The different types of food and how much of it you should be aiming to eat is demonstrated on the pyramid below.\"}" --output balanced_diet.wav "https://stream.watsonplatform.net/text-to-speech/api/v1/synthesize"