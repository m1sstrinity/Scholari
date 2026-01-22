<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template match="/">
    <html>
      <head>
        <title>Projects and Tasks</title>
        <link rel="stylesheet" href="css/dashboard.css"/>
        <style>
          body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 0;
          }

          main {
            padding: 20px;
          }

          main h2 {
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 28px;
          }

          table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 5px #ddd;
          }

          thead {
            background: #5271ff;
            color: white;
            text-align: left;
          }

          thead th {
            padding: 12px 15px;
          }

          tbody tr:hover {
            background: #f5faff;
          }

          tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
          }

          .status-ongoing {
            color: #b37b00;
            font-weight: 600;
          }

          .status-accomplished {
            color: #2a8f2a;
            font-weight: 600;
          }
        </style>
      </head>
      <body>
        <aside>
          <div class="logo"><img src="sclogo.png" alt="Logo"/></div>
          <a href="dashboard.html"><button>📊 Dashboard</button></a>
          <a href="deptlist.html"><button>📋 Department List</button></a>
          <a href="users.xml"><button>👥 Users</button></a>
          <a href="projtask.xml"><button class="active">🗓️ Project and Task</button></a>
          <a href="settings.html"><button>⚙️ System Settings</button></a>
          <a href="logout.html"><button>🚪 Log out</button></a>
        </aside>

        <main>
          <h2>Project and Tasks</h2>
          <table>
            <thead>
              <tr>
                <th>Name</th>
                <th>Status</th>
                <th>Department</th>
                <th>Project/Task</th>
                <th>Assignee</th>
                <th>Adviser</th>
              </tr>
            </thead>
            <tbody>
              <xsl:for-each select="projects/project">
                <tr>
                  <td><xsl:value-of select="name"/></td>
                  <td>
                    <xsl:attribute name="class">
                      <xsl:choose>
                        <xsl:when test="status='Ongoing'">status-ongoing</xsl:when>
                        <xsl:otherwise>status-accomplished</xsl:otherwise>
                      </xsl:choose>
                    </xsl:attribute>
                    <xsl:value-of select="status"/>
                  </td>
                  <td><xsl:value-of select="department"/></td>
                  <td><xsl:value-of select="task"/></td>
                  <td><xsl:value-of select="assignee"/></td>
                  <td><xsl:value-of select="adviser"/></td>
                </tr>
              </xsl:for-each>
            </tbody>
          </table>
        </main>
      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
