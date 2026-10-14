
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Category Add-on Relationships</title>

<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    padding: 32px 16px;
    background: #f2f4f6;
    color: #20262d;
    font: 15px/1.5 system-ui, sans-serif;
}

.app {
    max-width: 950px;
    margin: auto;
    background: white;
    border: 1px solid #d9dee4;
    border-radius: 12px;
    padding: 26px;
}

h1 {
    font-size: 23px;
    margin: 0;
}

h2 {
    font-size: 18px;
    margin: 0 0 12px;
}

h3 {
    font-size: 15px;
    margin: 0 0 9px;
}

.muted {
    color: #68717b;
    font-size: 13px;
}

button {
    cursor: pointer;
    font: inherit;
    border: 1px solid #c5cbd2;
    border-radius: 7px;
    padding: 9px 14px;
    background: white;
}

button:hover {
    border-color: #28a94a;
}

.layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}

.panel {
    border: 1px solid #d9dee4;
    border-radius: 9px;
    padding: 17px;
    min-width: 0;
}

.field {
    margin-bottom: 15px;
}

label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 5px;
}

input, select {
    width: 100%;
    border: 1px solid #c5cbd2;
    border-radius: 6px;
    padding: 10px 12px;
    font: inherit;
    background: white;
}

.item {
    width: 100%;
    display: block;
    text-align: left;
    margin: 7px 0;
}

.item.active {
    border-color: #28a94a;
    background: #effaf1;
}

.note {
    background: #f7f8fa;
    border-left: 3px solid #28a94a;
    padding: 11px 13px;
    font-size: 13px;
    margin: 18px 0;
}

.tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.tag {
    border-radius: 999px;
    padding: 7px 12px;
}

.tag.selected {
    background: #28a94a;
    color: white;
    border-color: #198137;
}

.summary {
    margin-top: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
}

.summary p {
    margin: 4px 0;
}

.hidden {
    display: none !important;
}

@media (max-width: 650px) {
    .layout {
        grid-template-columns: 1fr;
    }

    .app {
        padding: 18px;
    }
}
</style>
</head>

<body>

<main class="app">

    <h1>Category Add-on Relationships</h1>

    <p class="muted">
        Interactive prototype — sample data only.
    </p>

    <!-- Main Dropdown -->

    <div class="field" style="margin-top:22px; max-width:330px;">
        <label for="relationship-type">Type</label>

        <select id="relationship-type">
            <option value="subcategory">Subcategory</option>
            <option value="addon">Add-on</option>
        </select>
    </div>

    <!-- SUBCATEGORY MODE -->

    <div id="subcategory-mode">

        <h2>Attach Add-on Options to a Subcategory</h2>

        <div class="note">
            Only add-ons and subcategories with matching
            <strong>Event ID, City ID, and Package ID</strong>
            can be linked.
        </div>

        <div class="layout">

            <section class="panel">

                <h3>1. Search Add-ons by Name</h3>

                <input
                    id="addon-search"
                    type="search"
                    placeholder="Search add-on name..."
                >

                <div id="addon-results"></div>

            </section>

            <section class="panel">

                <h3>2. Search Options in Selected Add-on</h3>

                <p
                    class="muted"
                    id="selected-addon-label"
                >
                    Select an add-on first.
                </p>

                <input
                    id="option-search"
                    type="search"
                    placeholder="Search option name..."
                    disabled
                >

                <div
                    id="option-results"
                    class="tags"
                    style="margin-top:12px;"
                ></div>

            </section>

        </div>

        <div class="summary">

            <h3>Selected Relationships</h3>

            <div
                id="subcategory-summary"
                class="muted"
            ></div>

        </div>

    </div>

    <!-- ADD-ON MODE -->

    <div id="addon-mode" class="hidden">

        <h2>Attach an Add-on to Subcategories</h2>

        <div class="note">
            Search and link by
            <strong>subcategory name</strong>,
            not by an individual subcategory record ID.

            Relationships are only valid within the same
            <strong>Event ID, City ID, and Package ID</strong>.
        </div>

        <section class="panel">

            <h3>Search Subcategories by Name</h3>

            <input
                id="subcategory-search"
                type="search"
                placeholder="Search subcategory name..."
            >

            <div id="subcategory-results"></div>

        </section>

        <div class="summary">

            <h3>Linked Subcategory Names</h3>

            <div
                id="addon-summary"
                class="muted"
            ></div>

        </div>

    </div>

</main>

<script>
"use strict";

// Demo data: no database connection.

const subcategories = [
    {
        id: 101,
        name: "Linens",
        eventId: 1,
        cityId: 10,
        packageId: 100
    },
    {
        id: 102,
        name: "Tables",
        eventId: 1,
        cityId: 10,
        packageId: 100
    },
    {
        id: 103,
        name: "Chairs",
        eventId: 1,
        cityId: 10,
        packageId: 100
    },
    {
        id: 104,
        name: "Linens",
        eventId: 2,
        cityId: 20,
        packageId: 200
    },
    {
        id: 105,
        name: "Decor",
        eventId: 1,
        cityId: 10,
        packageId: 100
    }
];

const addons = [
    {
        id: 201,
        name: "Tables",
        eventId: 1,
        cityId: 10,
        packageId: 100,
        options: [
            { id: 1, name: "Round Table" },
            { id: 2, name: "Rectangular Table" },
            { id: 3, name: "Cocktail Table" }
        ]
    },
    {
        id: 202,
        name: "Photography",
        eventId: 1,
        cityId: 10,
        packageId: 100,
        options: [
            { id: 4, name: "Basic" },
            { id: 5, name: "Premium" }
        ]
    },
    {
        id: 203,
        name: "Catering",
        eventId: 2,
        cityId: 20,
        packageId: 200,
        options: [
            { id: 6, name: "Buffet" }
        ]
    },
    {
        id: 204,
        name: "Decor",
        eventId: 1,
        cityId: 10,
        packageId: 100,
        options: [
            { id: 7, name: "Flowers" },
            { id: 8, name: "Centerpieces" }
        ]
    }
];

const $ = id => document.getElementById(id);

// Relationship validation
function matchContext(a, b) {
    return (
        a.eventId === b.eventId &&
        a.cityId === b.cityId &&
        a.packageId === b.packageId
    );
}

// Case-insensitive name search
function matches(name, query) {
    return name.toLowerCase().includes(
        query.trim().toLowerCase()
    );
}

// Demo selection state
const selectedOptions = new Map();
const selectedNames = new Map();

// Simulated context for the current CMS record.
// These IDs are not displayed as a selector.
let currentSubcategoryId = 101;
let currentAddonId = 201;
let chosenAddonId = null;

function element(tag, className, text) {
    const el = document.createElement(tag);

    if (className) {
        el.className = className;
    }

    if (text !== undefined) {
        el.textContent = text;
    }

    return el;
}

// ==========================================
// SUBCATEGORY MODE
// ==========================================

function renderAddonResults() {
    const parent = subcategories.find(
        x => x.id === currentSubcategoryId
    );

    const root = $("addon-results");
    root.replaceChildren();

    const available = addons.filter(a =>
        matchContext(a, parent) &&
        matches(a.name, $("addon-search").value)
    );

    if (!available.length) {
        root.append(
            element(
                "p",
                "muted",
                "No matching add-ons in this event, city, and package."
            )
        );
    }

    available.forEach(addon => {
        const button = element(
            "button",
            "item" +
            (chosenAddonId === addon.id ? " active" : "")
        );

        button.type = "button";

        button.append(
            element("strong", "", addon.name),
            element(
                "div",
                "muted",
                `${addon.options.length} options`
            )
        );

        button.onclick = () => {
            chosenAddonId = addon.id;
            $("option-search").value = "";
            renderSubcategoryMode();
        };

        root.append(button);
    });
}

function renderOptions() {
    const addon = addons.find(
        x => x.id === chosenAddonId
    );

    const root = $("option-results");
    root.replaceChildren();

    $("option-search").disabled = !addon;

    $("selected-addon-label").textContent = addon
        ? `Options for ${addon.name}`
        : "Select an add-on first.";

    if (!addon) return;

    const key = `${currentSubcategoryId}:${addon.id}`;

    const selected =
        selectedOptions.get(key) || new Set();

    const options = addon.options.filter(option =>
        matches(option.name, $("option-search").value)
    );

    if (!options.length) {
        root.append(
            element("p", "muted", "No matching options.")
        );
    }

    options.forEach(option => {
        const active = selected.has(option.id);

        const button = element(
            "button",
            "tag" + (active ? " selected" : ""),
            (active ? "✓ " : "") + option.name
        );

        button.type = "button";

        button.setAttribute(
            "aria-pressed",
            String(active)
        );

        button.onclick = () => {
            const next = new Set(
                selectedOptions.get(key) || []
            );

            if (next.has(option.id)) {
                next.delete(option.id);
            } else {
                next.add(option.id);
            }

            if (next.size) {
                selectedOptions.set(key, next);
            } else {
                selectedOptions.delete(key);
            }

            renderSubcategoryMode();
        };

        root.append(button);
    });
}

function renderSubcategorySummary() {
    const root = $("subcategory-summary");
    root.replaceChildren();

    let count = 0;

    for (const addon of addons) {
        const ids = selectedOptions.get(
            `${currentSubcategoryId}:${addon.id}`
        );

        if (!ids || !ids.size) continue;

        count++;

        const names = addon.options
            .filter(option => ids.has(option.id))
            .map(option => option.name)
            .join(", ");

        root.append(
            element("p", "", `${addon.name}: ${names}`)
        );
    }

    if (!count) {
        root.textContent =
            "No add-on options selected yet.";
    }
}

function renderSubcategoryMode() {
    renderAddonResults();
    renderOptions();
    renderSubcategorySummary();
}

// ==========================================
// ADD-ON MODE
// ==========================================

function renderSubcategoryResults() {
    const addon = addons.find(
        a => a.id === currentAddonId
    );

    const root = $("subcategory-results");
    root.replaceChildren();

    // Match by subcategory NAME.
    // Event, city and package must also match.
    const names = [
        ...new Set(
            subcategories
                .filter(s =>
                    matchContext(s, addon) &&
                    matches(
                        s.name,
                        $("subcategory-search").value
                    )
                )
                .map(s => s.name)
        )
    ];

    if (!names.length) {
        root.append(
            element(
                "p",
                "muted",
                "No matching subcategory names in this event, city, and package."
            )
        );
    }

    names.forEach(name => {
        const selected =
            selectedNames.get(addon.id) || new Set();

        const active = selected.has(name);

        const button = element(
            "button",
            "item" + (active ? " active" : ""),
            (active ? "✓ " : "") + name
        );

        button.type = "button";

        button.setAttribute(
            "aria-pressed",
            String(active)
        );

        button.onclick = () => {
            const next = new Set(
                selectedNames.get(addon.id) || []
            );

            if (next.has(name)) {
                next.delete(name);
            } else {
                next.add(name);
            }

            selectedNames.set(addon.id, next);

            renderAddonMode();
        };

        root.append(button);
    });
}

function renderAddonMode() {
    renderSubcategoryResults();

    const names = [
        ...(selectedNames.get(currentAddonId) || [])
    ];

    $("addon-summary").textContent = names.length
        ? names.join(", ")
        : "No subcategory names linked yet.";
}

// ==========================================
// EVENTS
// ==========================================

$("addon-search").oninput = renderAddonResults;

$("option-search").oninput = renderOptions;

$("subcategory-search").oninput =
    renderSubcategoryResults;

// Switch between the two modes
function switchMode(mode) {
    const isSubcategory = mode === "subcategory";

    $("subcategory-mode").classList.toggle(
        "hidden",
        !isSubcategory
    );

    $("addon-mode").classList.toggle(
        "hidden",
        isSubcategory
    );
}

$("relationship-type").onchange = event => {
    switchMode(event.target.value);
};

// Initial rendering
renderSubcategoryMode();
renderAddonMode();

</script>

</body>
</html>
