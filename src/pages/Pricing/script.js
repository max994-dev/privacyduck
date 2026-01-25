
window.addEventListener('DOMContentLoaded', function () {
    toggleCollapse();
    const data = {
        one: {
            single: {
                title: "Standard Protection",
                cond: "1Year 1Person",
                price: "$10.75<span class='text-[16px]'>/mo</span>",
                billed: "Billed annually($129.00/year)",
                subtitle: "Standard PrivacyDuck protection plan for 1 person.",
                content: "Remove yourself from all major data broker websites for 1 year."
            },
            couple: {
                title: "Standard Protection",
                cond: "1Year 2Person",
                price: "$19.08<span class='text-[16px]'>/mo</span>",
                billed: "Billed annually($229.00/year)",
                subtitle: "Most popular PrivacyDuck protection plan for 2 people.",
                content: "Remove 2 people from all major data broker websites for 1 year."
            },
            family: {
                title: "Family Protection",
                cond: "1Year 4Person",
                price: "$27.42<span class='text-[16px]'>/mo</span>",
                billed: "Billed annually($329.00/year)",
                subtitle: "Standard PrivacyDuck protection plan for the entire family.",
                content: "Remove 4 people from all major data broker websites for 1 year."
            },
    
        },
        two: {
            single: {
                title: "Standard Protection",
                cond: "2Year 1Person",
                price: "$8.71<span class='text-[16px]'>/mo</span>",
                billed: "Billed annually($209.00/2 years)",
                subtitle: "Standard PrivacyDuck protection plan for 1 person.",
                content: "Remove 2 people from all major data broker websites for 1 year."
            },
            couple: {
                title: "Standard Protection",
                cond: "2Year, 2Person",
                price: "$8.71<span class='text-[16px]'>/mo</span>",
                billed: "Billed annually($209.00/2 years)",
                subtitle: "Most popular PrivacyDuck protection plan for 2 people.",
                content: "Remove 2 people from all major data broker websites for 2 year."
            },
            family: {
                title: "Family Protection",
                cond: "2Year, 4Person",
                price: "$20.79<span class='text-[16px]'>/mo</span>",
                billed: "Billed annually($449.00/2 years)",
                subtitle: "Standard PrivacyDuck protection plan for the entire family.",
                content: "Remove 4 people from all major data broker websites for 2 years."
            },
    
        }
    }
    
    function handleChange(yearKey, peopleKey) {
        const plan = data[yearKey]?.[peopleKey];
    
        if (!plan) return;
    
        $("#title").text(plan.title);
        $("#cond").text(plan.cond);
        $("#price").html(plan.price);
        $("#billed").text(plan.billed);
        $("#subtitle").text(plan.subtitle);
        $("#content").text(plan.content);
    }
    
    const yearButtons = $("[data-type='year_one'], [data-type='year_two']");
    const peopleButtons = $("[data-type='single_type'], [data-type='couple_type'], [data-type='family_type']");
    
    yearButtons.click(function () {
        yearButtons.removeClass("bg-[#24A556] font-bold active");
        $(this).addClass("bg-[#24A556] font-bold active");
        const year = $(this).attr("data-type") === "year_one" ? "one" : "two";
        const activePeopleButton = $('#people button.active').attr("data-type").split("_").filter(Boolean).length > 0 && $('#people button.active').attr("data-type").split("_").filter(Boolean)[0];
    
        handleChange(year, activePeopleButton);
    });
    peopleButtons.click(function () {
        peopleButtons.removeClass("bg-[#24A556] font-bold active");
        $(this).addClass("bg-[#24A556] font-bold active");
        const people = $(this).attr("data-type").split("_").filter(Boolean).length > 0 && $(this).attr("data-type").split("_").filter(Boolean)[0];
        const activeYearButton = $('#year button.active').attr("data-type").split("_").filter(Boolean).length > 0 && $('#year button.active').attr("data-type").split("_").filter(Boolean)[1];
    
        handleChange(activeYearButton, people);
    });

    //------------------------------------------------

    $("#pay").click(function(){
        window.location.href = "payment";        
    })
})
