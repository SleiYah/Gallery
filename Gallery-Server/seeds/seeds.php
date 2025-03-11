<?php

include_once("../models/User.php");
include_once("../models/Faq.php");
include("../connection/connection.php");

User::setUser("Halim Njeim", "michaelnjeim44@gmail.com", "CaptainSlash");
User::saveUser();


$faqs = [["question" => "What is the primary purpose of Reference Architectures (RAs)?",
        "answer" => "The primary purpose of RAs is to capture the essence of existing architectures and provide guidance for developing new system architectures. They help reuse proven patterns, reduce design effort, and ensure consistency across projects."],
        ["question" => "How do RAs balance abstraction and specificity?",
        "answer" => "RAs maintain a high level of abstraction, meaning they are not too specific but provide enough detail to be understandable. This allows them to be applicable across different projects while still being useful in guiding architectural decisions."],
        ["question" => "What are the key elements that define a system, according to the report?",
        "answer" => "A system is defined by the combination of a creator (architect), a structure (architecture), a design pattern (problem-solution approach), and a set of rules (architecture framework)."],
        ["question" => "How do different companies interpret the concept of Reference Architecture?",
        "answer" => "Sun Computer views Reference Architecture as a guide for system design and risk reduction. Burton Group Research considers it a framework built from industry experience. BEA Systems defines it as a set of tools, templates, and structured methods to implement projects efficiently."],
        ["question" => "What role do patterns play in Reference Architectures?",
        "answer" => "Patterns provide solutions to common design problems, ensuring best practices are reused instead of reinventing solutions. They contribute to efficiency, cost reduction, and consistency across different projects."],
        ["question" => "What are the key principles of a well-structured Reference Architecture?",
        "answer" => "A good Reference Architecture must be adaptable, efficient, legally compliant, based on quality data, and act as a flexible foundation that does not restrict future technological decisions."],
        ["question" => "How do Reference Architectures evolve and improve?",
        "answer" => "They improve through continuous feedback. After being used in designing system architectures, their effectiveness is evaluated, and adjustments are made based on real-world challenges and opportunities for improvement."],
        ["question" => "What is the difference between implicit and explicit knowledge in Reference Architectures?",
        "answer" => "Implicit knowledge exists within individuals or teams but is undocumented, while explicit knowledge is recorded and shared. Reference Architectures aim to transform implicit knowledge into explicit knowledge through documentation and structured patterns."],
        ["question" => "Why is it important to maintain and update Reference Architectures?",
        "answer" => "Regular updates ensure that Reference Architectures remain relevant and effective. Without maintenance, they risk becoming outdated and losing their ability to provide meaningful guidance."],
        ["question" => "How can Reference Architectures be simply described?",
        "answer" => "A Reference Architecture is a structured blueprint that helps design projects by aligning business, technical, and customer needs while ensuring adaptability, efficiency, and reusability."]];

foreach($faqs as $faq){
    Faq::setFaq($faq["question"], $faq["answer"]);
    Faq::saveFaq();
}
?>