/*
    New translations for admin panels (and other not translated texts)
    in FR Ryd booking system.
*/

-- Menu items
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("profile_menu_title",1,"Profile");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("profile_menu_title",2,"Profil");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("sessions_menu_title",1,"Session");
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

-- Instructions for sessions selection on session Page
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("session_select_instructions",1,"Click on a day with a session to get to that session menu.<br /> Click on a free day to add a session.");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("session_select_instructions",2,"Klicka på datumet för en dag med ett pass för att komma till dess pass-meny.<br /> Klicka på ett ledigt datum för att lägga till ett pass där.");

-- Color info for session page
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("color_info_green",1,"Green &nbsp;(To be lended out!)");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("color_info_green",2,"Grön &nbsp;(Skall Utlämnas!)");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("color_info_red",1,"Red &nbsp;(To be returned!)");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("color_info_red",2,"Röd &nbsp;&nbsp;(Skall Återlämnas!)");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("color_info_yellow",1,"Yellow &nbsp;(Done, has been returned or lended out!)");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("color_info_yellow",2,"Gul &nbsp;&nbsp;&nbsp;(Klart! Varan har Återlämnats eller Utlämnats)");

-- Misc text on session page
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("session_summary",1,"This is what you have to look forward to this session!");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("session_summary",2,"Detta har ni att se fram emot detta pass!");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("items_booked",1,"items booked from this session.");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("items_booked",2,"föremål bokade för utlåmning detta pass.");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("items_to_return",1,"items to be returned this session.");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("items_to_return",2,"föremål bokade ska lämnas åter detta pass.");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("lend_out_box",1,'<h2 >Pick Ups</h2><p><i>These are the items booked to be picked up this session.</i></p>');
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("lend_out_box",2,'<h2 >Utlämningar</h2><p><i>Här listas bokningar för föremål som bokats för att hämtas ut detta pass.</i></p>');

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("return_box",1,'<h2>Returns</h2><p><i>These are the items to be returned this session.</i></p>');
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("return_box",2,'<h2>Återlämningar</h2><p><i>Här listas bokningar för föremål som bokats för att återlämnas detta pass.</i></p>');

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("everything_out_box",1,'<h2>Everything lended out</h2><p><i>These are all items lended out but not yet returned.</i></p>');
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("everything_out_box",2,'<h2>Allt utlånat</h2><p><i>Här listas allt som lånats ut men inte lämnats tillbaka.</i></p>');

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("no_items_lended",1,"No items are lended out.");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("no_items_lended",2,"Inga föremål är utlåmnade.");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("remove",1,"Remove");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("remove",2,"Ta bort");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("add_booking",1,"Add booking");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("add_booking",2,"Lägg till bokning");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("between",1,"between");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("between",2,"mellan");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("and",1,"and");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("and",2,"och");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("comment",1,"comment");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("comment",2,"kommentar");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("update",1,"update");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("update",2,"uppdatera");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("lended_out",1,"Lended out");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("lended_out",2,"Utlämnad");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("returned",1,"Returned");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("returned",2,"Återlämnad");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("delayed",1,"Delayed");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("delayed",2,"Försenad");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("all_lended",1,"All lended out");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("all_lended",2,"Alla utlämnade");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("edit_booking",1,"Edit booking");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("edit_booking",2,"Redigera bokning");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("all_returned",1,"All returned");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("all_returned",2,"Alla återlämnade");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("removed",1,"removed");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("removed",2,"borttagen");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("created",1,"created");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("created",2,"skapat");

INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("session_remove_error",1,"You can not remove this session when there exists booking to or from it.\n Remove or change them first.\n");
INSERT INTO `translations`(`name`, `language`, `value`) VALUES ("session_remove_error",2,"Du kan inte ta bort detta pass då det finns bokningar till eller från detta.\n Ta bort eller ändra dem först.\n");


----------------- ADDED ----------------
