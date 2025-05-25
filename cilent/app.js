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

    try {
        const response = await fetch("http://127.0.0.1:5000/predict", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(requestData)
        });

        const result = await response.json();
        if (result.res === "Success") {
            document.getElementById("result").textContent = `Recommended Crop: ${result.output}`;
        } else {
            document.getElementById("result").textContent = "Error: Unable to predict crop.";
        }
    } catch (error) {
        document.getElementById("result").textContent = "Error: Failed to fetch prediction.";
        console.error("Error:", error);
    }
});
