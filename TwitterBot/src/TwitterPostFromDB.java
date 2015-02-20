import java.sql.*;
import java.text.DateFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;

/**
* Created by killdon on 18.02.15.
*/
public class TwitterPostFromDB {

    public class WorkWithDB {
        private static final long MEC_TIME_TO_GO_OFFLINE = 1000 * 60 * 15;
        String user = "root";
        String password = "mec2015";
        String url = "jdbc:mysql://localhost:3306/geo";
        String driver = "com.mysql.jdbc.Driver";

        Date timeNow = null;
        Date timeMec = null;

        SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy.MM.dd HH.mm.ss");

        private String getParameterByName(String parameter, int numFromEnd) {
            String result = null;
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

                while (rs.next() && numFromEnd >= 0) {
                    result = rs.getString(parameter);
                    numFromEnd--;
                }
            } catch (Exception e) {
                e.printStackTrace();
            } finally {
                try {
                    if (c != null)
                        c.close();int currentId = getNumOfId();
                } catch (SQLException e) {
                    // TODO Auto-generated catch block
                    e.printStackTrace();
                }
            }
            return result;
        }

        private int getNumOfId() {
            return Integer.parseInt(getLastParameterByName("id"));
        }

        public boolean isParameterDeltaBig(String parameter) throws ParseException {
            return ((Double.parseDouble(getParameterByName(parameter, 0)) * 0.2) >= getParameterDelta(parameter, 10, 20)) && isMecOnline();
        }

        private String getLastParameterByName(String parameter) {
            return getParameterByName(parameter, 0);
        }
        private double getParameterDelta(String parameter, int minTime, int maxTime) {
            int currentNumOfString = 0;
            float deltaTime = 0;
            while (true) {
                try {
                    deltaTime = (dateFormat.parse(getParameterByName("time", 0)).getTime() - (dateFormat.parse(getParameterByName("time", currentNumOfString)).getTime()));
                } catch (ParseException e) {
                    e.printStackTrace();
                }
                if (deltaTime <= maxTime || deltaTime >= minTime) {
                    return Math.abs(Double.parseDouble(getParameterByName(parameter, 0)) - Double.parseDouble(getParameterByName(parameter, currentNumOfString)));
                } else if(currentNumOfString >= 200) {
                    return 0;
                }

            }

        }
        private boolean isMecOnline() throws ParseException {
            return ((new Date()).getTime() - (dateFormat.parse(getParameterByName("time", 0)).getTime())) >= MEC_TIME_TO_GO_OFFLINE;
        }
    }
}
