function openTab(tabId) {
    document.querySelectorAll(".tab-button").forEach(btn => btn.classList.remove("active"));
    event.target.classList.add("active");

    document.querySelectorAll(".tab-content").forEach(tab => tab.classList.remove("active"));
    document.getElementById(tabId).classList.add("active");
}