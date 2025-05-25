document.getElementById("crop-form").addEventListener("submit", async function (event) {
    event.preventDefault(); // Prevent form reload

    const nitrogen = document.getElementById("nitrogen").value;
    const phosphorus = document.getElementById("phosphorus").value;
    const potassium = document.getElementById("potassium").value;
    const temperature = document.getElementById("temperature").value;
    const humidity = document.getElementById("humidity").value;
    const ph = document.getElementById("ph").value;
    const rainfall = document.getElementById("rainfall").value;

    const requestData = {
        N: nitrogen,
        P: phosphorus,
        K: potassium,
        temperature: temperature,
        humidity: humidity,
        pH: ph,
        rainfall: rainfall
    };

    console.log("Sending request with data:", requestData);

    try {
        const response = await fetch("http://127.0.0.1:5000/predict", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(requestData)
        });

        console.log("Response received, status:", response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const result = await response.json();
        console.log("Parsed result:", result);

        const resultDiv = document.getElementById("result");
        if (result.res === "Success" && result.output) {
            resultDiv.textContent = `Recommended Crop: ${result.output}`;
            resultDiv.style.display = "block";
        } else {
            resultDiv.textContent = "Error: No valid crop recommendation received.";
            resultDiv.style.display = "block";
        }
    } catch (error) {
        console.error("Error:", error);
        const resultDiv = document.getElementById("result");
        resultDiv.textContent = `Error: Failed to fetch prediction. Details: ${error.message}`;
        resultDiv.style.display = "block";
    }
});