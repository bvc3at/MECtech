import twitter4j.*;
import twitter4j.auth.AccessToken;

import java.io.IOException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

public class TwitterPost {

    WorkWithDB workWithDB = new WorkWithDB();
    SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy.MM.dd HH.mm.ss");

    Date timeNow = null;
    Date timeMec = null;



    private final static String CONSUMER_KEY = "U7TKX9Z3VYbpTo3pelD9O0Dpy";
    private final static String CONSUMER_KEY_SECRET =
            "YbgO3mL0OruWkjo6wEF7vgbL23LUcDdUY62VNY9dFKZydgW4uI";

    public void start() throws TwitterException, IOException, InterruptedException, ParseException {

        ArrayList<Long>  listOfProcessedMentionsById = new ArrayList<Long>();


        Twitter twitter = new TwitterFactory().getInstance();
        twitter.setOAuthConsumer(CONSUMER_KEY, CONSUMER_KEY_SECRET);

        String accessToken = getSavedAccessToken();
        String accessTokenSecret = getSavedAccessTokenSecret();
        AccessToken oathAccessToken = new AccessToken(accessToken,
                accessTokenSecret);

        twitter.setOAuthAccessToken(oathAccessToken);



        while (true) {
            List<Status> mentions = twitter.getMentionsTimeline();
            workWithMentions:for (Status status : mentions) {
                for (Long mentionsId : listOfProcessedMentionsById) {
                    if (status.getId() == mentionsId) {
                        continue workWithMentions;
                    }
                }
                doReplyToMention(status, twitter);
                listOfProcessedMentionsById.add(status.getId());
            }
            Thread.sleep(60*5000);
        }
    }
    private void doReplyToMention(Status mentionStatus, Twitter twitter) throws TwitterException, ParseException {

        timeNow = new Date();
        timeMec = dateFormat.parse(workWithDB.getLastParameterByName("time"));

        String str = " ";

        if ((timeNow.getTime() - timeMec.getTime()) < 5 * 60 * 1000) {
            str += "МЭК в сети, ";
        } else {
            str += "МЭК не в сети. Последний раз был в сети около " + getFormattedStringTimeAgo(timeNow.getTime() - timeMec.getTime()) + ", ";
        }


        ArrayList<String[]> listOfRequestParametersFromTwit = getListOfRequestParametersFromTwit(mentionStatus.getText());
        if (mentionStatus.getUser().getScreenName().equals(twitter.getScreenName())) {
            return;
        }
        if (listOfRequestParametersFromTwit.size() > 0) {
            for (int i = 0; i < listOfRequestParametersFromTwit.size(); i++) {
                str += listOfRequestParametersFromTwit.get(i)[0] + workWithDB.getLastParameterByName(listOfRequestParametersFromTwit.get(i)[1]) + " " + listOfRequestParametersFromTwit.get(i)[2];
                if (str.length() >= 120) {
                    break;
                }
                if (i + 1 < listOfRequestParametersFromTwit.size()) {
                    str += ", ";
                }

            }
            StatusUpdate statusText = new StatusUpdate("@" + mentionStatus.getUser().getScreenName() + str);
            statusText.setInReplyToStatusId(mentionStatus.getId());
            statusText.setLocation(new GeoLocation(getDoubleFromString(workWithDB.getLastParameterByName("lati")), getDoubleFromString(workWithDB.getLastParameterByName("longe"))));
            try {
                twitter.updateStatus(statusText);
            } catch (Exception e) {
                e.fillInStackTrace();
            }

        }
    }
    private ArrayList<String[]> getListOfRequestParametersFromTwit(String twit) {
        ArrayList<String[]> listOfParameters = new ArrayList<>();
        if (isThatParameterRequestInThatTwit("че как", twit) || isThatParameterRequestInThatTwit("состояние", twit) || isThatParameterRequestInThatTwit("дела", twit)) {
            listOfParameters.add(new String[]{"температура: ", "temp", "℃"});
            listOfParameters.add(new String[]{"влажность: ", "humid", "%"});
            listOfParameters.add(new String[]{"давление: ", "press", "мм"});
            listOfParameters.add(new String[]{"угол: ", "tilt", "°"});
            listOfParameters.add(new String[]{"азимут: ", "azi", "°"});

        } else {
            if (isThatParameterRequestInThatTwit("темп", twit) || isThatParameterRequestInThatTwit("жарк", twit)
                    || isThatParameterRequestInThatTwit("холод", twit) || isThatParameterRequestInThatTwit("погод", twit) || isThatParameterRequestInThatTwit("термо", twit) || isThatParameterRequestInThatTwit("хлад", twit) || isThatParameterRequestInThatTwit("замерз", twit)) {
                listOfParameters.add(new String[]{"температура: ", "temp", "℃"});
            }
            if (isThatParameterRequestInThatTwit("влаж", twit) || isThatParameterRequestInThatTwit("мок", twit)) {
                listOfParameters.add(new String[]{"влажность: ", "humid", "%"});
            }
            if (isThatParameterRequestInThatTwit("давл", twit) || isThatParameterRequestInThatTwit("атмосф", twit)) {
                listOfParameters.add(new String[]{"давление: ", "press", "мм"});
            }
            if (isThatParameterRequestInThatTwit("угол", twit)) {
                listOfParameters.add(new String[]{"угол: ", "tilt", "°"});
            }
            if (isThatParameterRequestInThatTwit("азим", twit)) {
                listOfParameters.add(new String[]{"азимут: ", "azi", "°"});

            }
        }

        return listOfParameters;

    }
    private double getDoubleFromString(String str) {
        str = str.substring(0, str.length()-1);
        return Double.parseDouble(str);

    }
    private static String getFormattedStringTimeAgo(long deltaTime) {
        String result = null;
        if (deltaTime < 60000) {
            result = "только что";
        } else if (deltaTime < 60 * 60 * 1000) {
            result = (int)(deltaTime / 60000) + " минут назад";
        } else if (deltaTime < 24 * 60 * 60 * 1000) {
            result = (int)(deltaTime / (60*60*1000)) + " часов назад";
        } else {
            result = (int)(deltaTime / (24*60*60*1000)) + " дней назад";
        }
        return result;
    }
    private boolean isThatParameterRequestInThatTwit(String parameter, String twit) {
        return (twit.toLowerCase().lastIndexOf(parameter.toLowerCase()) != -1);
    }

    private String getSavedAccessTokenSecret() {
        return "rPtuCrO4zUuSJwLziCN7F05GmVGc9pyGdyjiJCBsI5dsc";
    }

    private String getSavedAccessToken() {
        return "2928439822-4e4fRK0WnXPNo8nVW5FFxQ1SRFKBOtDjTp15tUu";
    }

    public static void main(String[] args) throws Exception {
        new TwitterPost().start();
    }
    public class WorkWithDB {
        String user = "root";
        String password = "mec2015";
        String url = "jdbc:mysql://localhost:3306/geo";
        String driver = "com.mysql.jdbc.Driver";

        public String getLastParameterByName(String parameter) {
            try {
                Class.forName(driver);//Регистрируем драйвер
            } catch (ClassNotFoundException e) {
                // TODO Auto-generated catch block
                e.printStackTrace();
            }
            Connection c = null;//Соединение с БД
            try {
                c = DriverManager.getConnection(url, user, password);
                java.sql.Statement st = c.createStatement();
                ResultSet rs = st.executeQuery("SELECT * FROM serverget");

                while (rs.next()) {
                    return rs.getString(parameter);
                }
            } catch (Exception e) {
                e.printStackTrace();
            } finally {
                try {
                    if (c != null)
                        c.close();
                } catch (SQLException e) {
                    // TODO Auto-generated catch block
                    e.printStackTrace();
                }
            }
            return null;
        }

    }
}


