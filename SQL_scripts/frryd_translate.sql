/*
    New translations for admin panels (and other not translated texts)
    in FR Ryd booking system.
*/

-- Menu items
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("profile_menu_title",1,"Profile");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("profile_menu_title",2,"Profil");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("sessions_menu_title",1,"Sessions");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("sessions_menu_title",2,"Pass");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("items_menu_title",1,"Items");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("items_menu_title",2,"Föremål");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("users_menu_title",1,"Users");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("users_menu_title",2,"Användare");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("bookings_menu_title",1,"Bookings");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("bookings_menu_title",2,"Bokningar");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("language_menu_title",1,"Language");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("language_menu_title",2,"Språk");

-- Logged in as info on every page
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("logged_in_as",1,"Logged in as ");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("logged_in_as",2,"Inloggad som");

-- Contact box on the front page
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("contact_box",1,'<h2>Contact us</h2><p>If you any questions and/or suggesstions for the lending service, then contact us at <a href="mailto:intendent@frryd.se" style="color:white;line-height:1.8em;">intendent@frryd.se</a><br></p>If you have any suggestions or improvements for the webpage, then contact us at <a href="mailto:it@frryd.se" style="color:white;line-height:1.8em;">it@frryd.se.</a>');
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("contact_box",2,'<h2>Kontakta Oss</h2><p>Om du har några förslag gällande utlåningen kan du kontakta oss på <a href="mailto:intendent@frryd.se" style="color:white;line-height:1.8em;">intendent@frryd.se</a><br></p>Om du har några förslag gällande hemsidan kan du kontakta oss på <a href="mailto:it@frryd.se" style="color:white;line-height:1.8em;">it@frryd.se.</a>');

----------------- ADDED ----------------

-- Instructions for sessions selection on session Page
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("session_select_instructions",1,"Click on a day with a session to get to that session menu.<br /> Click on a free day to add a session.");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("session_select_instructions",2,"Klicka på datumet för en dag med ett pass för att komma till dess pass-meny.<br /> Klicka på ett ledigt datum för att lägga till ett pass där.");
